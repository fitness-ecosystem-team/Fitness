import React, { useState } from 'react';
import {
  View, Text, TextInput, TouchableOpacity, StyleSheet,
  ActivityIndicator, Alert, KeyboardAvoidingView, Platform, ScrollView,
} from 'react-native';
import { NativeStackNavigationProp } from '@react-navigation/native-stack';
import { useAuth } from '../../context/AuthContext';
import { colors } from '../../constants/colors';
import { fonts } from '../../constants/typography';

type Props = { navigation: NativeStackNavigationProp<any> };

export default function RegisterScreen({ navigation }: Props) {
  const { register } = useAuth();
  const [name, setName] = useState('');
  const [email, setEmail] = useState('');
  const [password, setPassword] = useState('');
  const [confirm, setConfirm] = useState('');
  const [loading, setLoading] = useState(false);

  const handleRegister = async () => {
    if (!name || !email || !password || !confirm) { Alert.alert('Error', 'Please fill in all fields'); return; }
    if (password !== confirm) { Alert.alert('Error', 'Passwords do not match'); return; }
    setLoading(true);
    try {
      await register(name, email, password, confirm);
    } catch (e: any) {
      const errors = e.response?.data?.errors;
      const message = errors
        ? Object.values(errors).flat().join('\n')
        : e.response?.data?.message ?? 'Registration failed';
      Alert.alert('Error', message);
    } finally {
      setLoading(false);
    }
  };

  return (
    <KeyboardAvoidingView style={styles.container} behavior={Platform.OS === 'ios' ? 'padding' : undefined}>
      <ScrollView contentContainerStyle={styles.scroll} keyboardShouldPersistTaps="handled">

        <View style={styles.logoArea}>
          <View style={styles.logoDot} />
          <Text style={styles.logoText}>FitCore</Text>
        </View>

        <View style={styles.header}>
          <Text style={styles.title}>Create account</Text>
          <Text style={styles.subtitle}>Start your fitness journey today</Text>
        </View>

        <View style={styles.form}>
          {[
            { label: 'Full Name', value: name, set: setName, placeholder: 'Alex Rivera', secure: false, keyboard: 'default' as const },
            { label: 'Email', value: email, set: setEmail, placeholder: 'you@example.com', secure: false, keyboard: 'email-address' as const },
            { label: 'Password', value: password, set: setPassword, placeholder: '••••••••', secure: true, keyboard: 'default' as const },
            { label: 'Confirm Password', value: confirm, set: setConfirm, placeholder: '••••••••', secure: true, keyboard: 'default' as const },
          ].map((f) => (
            <View key={f.label} style={styles.field}>
              <Text style={styles.label}>{f.label}</Text>
              <TextInput
                style={styles.input}
                placeholder={f.placeholder}
                placeholderTextColor={colors.textMuted}
                value={f.value}
                onChangeText={f.set}
                secureTextEntry={f.secure}
                keyboardType={f.keyboard}
                autoCapitalize={f.keyboard === 'email-address' ? 'none' : 'words'}
              />
            </View>
          ))}

          <TouchableOpacity
            style={[styles.button, loading && styles.buttonDisabled]}
            onPress={handleRegister}
            disabled={loading}
            activeOpacity={0.85}
          >
            {loading
              ? <ActivityIndicator color="#fff" />
              : <Text style={styles.buttonText}>Create Account</Text>}
          </TouchableOpacity>

          <TouchableOpacity style={styles.linkRow} onPress={() => navigation.navigate('Login')}>
            <Text style={styles.linkText}>Already have an account? <Text style={styles.link}>Sign In</Text></Text>
          </TouchableOpacity>
        </View>
      </ScrollView>
    </KeyboardAvoidingView>
  );
}

const styles = StyleSheet.create({
  container: { flex: 1, backgroundColor: colors.background },
  scroll: { flexGrow: 1, justifyContent: 'center', padding: 24 },
  logoArea: { flexDirection: 'row', alignItems: 'center', gap: 6, marginBottom: 40 },
  logoDot: { width: 4, height: 24, backgroundColor: colors.primary, borderRadius: 2 },
  logoText: { fontFamily: fonts.heading, fontSize: 20, color: colors.textPrimary, letterSpacing: 0.5 },
  header: { marginBottom: 28 },
  title: { fontFamily: fonts.heading, fontSize: 28, color: colors.textPrimary, marginBottom: 6 },
  subtitle: { fontFamily: fonts.body, fontSize: 15, color: colors.textSecondary },
  form: { gap: 14 },
  field: { gap: 8 },
  label: { fontFamily: fonts.bodySemi, fontSize: 13, color: colors.textSecondary },
  input: {
    backgroundColor: colors.surface,
    borderWidth: 1,
    borderColor: colors.border,
    borderRadius: 10,
    paddingHorizontal: 14,
    paddingVertical: 14,
    color: colors.textPrimary,
    fontFamily: fonts.body,
    fontSize: 15,
  },
  button: {
    backgroundColor: colors.primary,
    borderRadius: 10,
    paddingVertical: 16,
    alignItems: 'center',
    marginTop: 4,
  },
  buttonDisabled: { opacity: 0.6 },
  buttonText: { fontFamily: fonts.bodySemi, color: '#fff', fontSize: 15 },
  linkRow: { alignItems: 'center' },
  linkText: { fontFamily: fonts.body, color: colors.textSecondary, fontSize: 14 },
  link: { fontFamily: fonts.bodySemi, color: colors.primary },
});
