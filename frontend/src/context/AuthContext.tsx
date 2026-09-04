import React, { createContext, useContext, useEffect, useState } from 'react';
import AsyncStorage from '@react-native-async-storage/async-storage';
import client from '../api/client';

type User = {
  id: number;
  name: string;
  email: string;
};

type AuthContextType = {
  user: User | null;
  token: string | null;
  loading: boolean;
  login: (email: string, password: string) => Promise<void>;
  register: (name: string, email: string, password: string, passwordConfirmation: string) => Promise<void>;
  logout: () => Promise<void>;
};

const AuthContext = createContext<AuthContextType>({} as AuthContextType);

export function AuthProvider({ children }: { children: React.ReactNode }) {
  const [user, setUser] = useState<User | null>(null);
  const [token, setToken] = useState<string | null>(null);
  const [loading, setLoading] = useState(true);

  useEffect(() => {
    AsyncStorage.multiGet(['auth_token', 'auth_user']).then(([tokenPair, userPair]) => {
      if (tokenPair[1]) setToken(tokenPair[1]);
      if (userPair[1]) setUser(JSON.parse(userPair[1]));
      setLoading(false);
    });
  }, []);

  const login = async (email: string, password: string) => {
    const res = await client.post('/login', { email, password });
    const { token: t, user: u } = res.data;
    await AsyncStorage.multiSet([['auth_token', t], ['auth_user', JSON.stringify(u)]]);
    setToken(t);
    setUser(u);
  };

  const register = async (name: string, email: string, password: string, passwordConfirmation: string) => {
    const res = await client.post('/register', {
      name,
      email,
      password,
      password_confirmation: passwordConfirmation,
    });
    const { token: t, user: u } = res.data;
    await AsyncStorage.multiSet([['auth_token', t], ['auth_user', JSON.stringify(u)]]);
    setToken(t);
    setUser(u);
  };

  const logout = async () => {
    try {
      await client.post('/logout');
    } catch {}
    await AsyncStorage.multiRemove(['auth_token', 'auth_user']);
    setToken(null);
    setUser(null);
  };

  return (
    <AuthContext.Provider value={{ user, token, loading, login, register, logout }}>
      {children}
    </AuthContext.Provider>
  );
}

export const useAuth = () => useContext(AuthContext);
