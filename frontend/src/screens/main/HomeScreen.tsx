import React, { useEffect, useState } from 'react';
import {
  View, Text, StyleSheet, ScrollView, TouchableOpacity, ActivityIndicator,
} from 'react-native';
import { SafeAreaView } from 'react-native-safe-area-context';
import { useAuth } from '../../context/AuthContext';
import client from '../../api/client';
import { colors } from '../../constants/colors';
import { fonts } from '../../constants/typography';

type UserContext = {
  user: { name: string };
  subscription: { plan: string; can_access_features: boolean };
  active_goals: any[];
  activity_today: any;
  unread_notifications: number;
};

export default function HomeScreen() {
  const { logout } = useAuth();
  const [ctx, setCtx] = useState<UserContext | null>(null);
  const [loading, setLoading] = useState(true);

  useEffect(() => {
    client.get('/module/user-context')
      .then((res) => setCtx(res.data))
      .catch(() => {})
      .finally(() => setLoading(false));
  }, []);

  if (loading) {
    return (
      <View style={styles.center}>
        <ActivityIndicator color={colors.primary} size="large" />
      </View>
    );
  }

  const firstName = ctx?.user.name.split(' ')[0] ?? 'there';
  const isPro = ctx?.subscription.can_access_features;

  return (
    <SafeAreaView style={styles.container}>
      <ScrollView contentContainerStyle={styles.scroll} showsVerticalScrollIndicator={false}>

        {/* Header */}
        <View style={styles.header}>
          <View>
            <Text style={styles.greeting}>Good morning 👋</Text>
            <Text style={styles.name}>{firstName}</Text>
          </View>
          <TouchableOpacity onPress={logout} style={styles.logoutBtn} activeOpacity={0.7}>
            <Text style={styles.logoutText}>Sign out</Text>
          </TouchableOpacity>
        </View>

        {/* Plan badge */}
        <View style={[styles.planBanner, isPro ? styles.planBannerPro : styles.planBannerFree]}>
          <View style={styles.planLeft}>
            <View style={styles.planDot} />
            <Text style={styles.planLabel}>{isPro ? 'Pro Plan' : 'Free Plan'}</Text>
          </View>
          {!isPro && (
            <TouchableOpacity style={styles.upgradeBtn} activeOpacity={0.8}>
              <Text style={styles.upgradeBtnText}>Upgrade</Text>
            </TouchableOpacity>
          )}
        </View>

        {/* Today's activity */}
        <View style={styles.sectionHeader}>
          <View style={styles.sectionAccent} />
          <Text style={styles.sectionTitle}>Today's Activity</Text>
        </View>

        <View style={styles.statsRow}>
          <StatCard label="Steps" value={ctx?.activity_today?.steps_count ?? 0} color={colors.info} />
          <StatCard label="Calories" value={ctx?.activity_today?.calories_burned ?? 0} unit="kcal" color={colors.warning} />
          <StatCard label="Active min" value={ctx?.activity_today?.active_minutes ?? 0} color={colors.success} />
        </View>

        {/* Goals */}
        <View style={styles.sectionHeader}>
          <View style={styles.sectionAccent} />
          <Text style={styles.sectionTitle}>Active Goals</Text>
        </View>

        {ctx?.active_goals?.length ? (
          ctx.active_goals.map((g) => (
            <View key={g.id} style={styles.goalRow}>
              <View style={styles.goalDot} />
              <View style={styles.goalInfo}>
                <Text style={styles.goalTitle}>{g.title}</Text>
                <Text style={styles.goalCategory}>{g.category}</Text>
              </View>
              <View style={styles.goalStatus}>
                <Text style={styles.goalStatusText}>{g.status}</Text>
              </View>
            </View>
          ))
        ) : (
          <View style={styles.emptyCard}>
            <Text style={styles.emptyIcon}>🎯</Text>
            <Text style={styles.emptyText}>No active goals yet</Text>
            <Text style={styles.emptyHint}>Set a goal to start tracking your progress</Text>
          </View>
        )}

        {/* Notifications badge */}
        {(ctx?.unread_notifications ?? 0) > 0 && (
          <View style={styles.notifBanner}>
            <Text style={styles.notifText}>
              🔔 You have {ctx?.unread_notifications} unread notification{ctx!.unread_notifications > 1 ? 's' : ''}
            </Text>
          </View>
        )}
      </ScrollView>
    </SafeAreaView>
  );
}

function StatCard({ label, value, unit, color }: { label: string; value: number; unit?: string; color: string }) {
  return (
    <View style={styles.statCard}>
      <View style={[styles.statAccent, { backgroundColor: color + '20' }]}>
        <Text style={[styles.statValue, { color }]}>{value}</Text>
        {unit && <Text style={[styles.statUnit, { color }]}>{unit}</Text>}
      </View>
      <Text style={styles.statLabel}>{label}</Text>
    </View>
  );
}

const styles = StyleSheet.create({
  container: { flex: 1, backgroundColor: colors.background },
  center: { flex: 1, justifyContent: 'center', alignItems: 'center', backgroundColor: colors.background },
  scroll: { padding: 20, paddingBottom: 32, gap: 14 },

  header: { flexDirection: 'row', justifyContent: 'space-between', alignItems: 'flex-start', marginBottom: 4 },
  greeting: { fontFamily: fonts.body, fontSize: 13, color: colors.textSecondary, marginBottom: 2 },
  name: { fontFamily: fonts.heading, fontSize: 26, color: colors.textPrimary },
  logoutBtn: {
    paddingVertical: 7, paddingHorizontal: 13,
    backgroundColor: colors.surface, borderRadius: 8,
    borderWidth: 1, borderColor: colors.border,
  },
  logoutText: { fontFamily: fonts.bodySemi, color: colors.textSecondary, fontSize: 13 },

  planBanner: {
    flexDirection: 'row', justifyContent: 'space-between', alignItems: 'center',
    padding: 14, borderRadius: 12, borderWidth: 1,
  },
  planBannerPro: { backgroundColor: colors.primaryMuted, borderColor: colors.primaryBorder },
  planBannerFree: { backgroundColor: colors.surface, borderColor: colors.border },
  planLeft: { flexDirection: 'row', alignItems: 'center', gap: 8 },
  planDot: { width: 8, height: 8, borderRadius: 4, backgroundColor: colors.primary },
  planLabel: { fontFamily: fonts.bodySemi, fontSize: 14, color: colors.textPrimary },
  upgradeBtn: {
    backgroundColor: colors.primary, paddingVertical: 6, paddingHorizontal: 14,
    borderRadius: 7,
  },
  upgradeBtnText: { fontFamily: fonts.bodySemi, fontSize: 12, color: '#fff' },

  sectionHeader: { flexDirection: 'row', alignItems: 'center', gap: 8 },
  sectionAccent: { width: 3, height: 16, backgroundColor: colors.primary, borderRadius: 2 },
  sectionTitle: { fontFamily: fonts.headingSemi, fontSize: 15, color: colors.textPrimary },

  statsRow: { flexDirection: 'row', gap: 10 },
  statCard: {
    flex: 1, backgroundColor: colors.surface, borderRadius: 12,
    padding: 14, borderWidth: 1, borderColor: colors.border, alignItems: 'center', gap: 8,
  },
  statAccent: { borderRadius: 8, paddingVertical: 8, paddingHorizontal: 12, alignItems: 'center' },
  statValue: { fontFamily: fonts.heading, fontSize: 20 },
  statUnit: { fontFamily: fonts.body, fontSize: 10, marginTop: 1 },
  statLabel: { fontFamily: fonts.body, fontSize: 11, color: colors.textMuted },

  goalRow: {
    flexDirection: 'row', alignItems: 'center', gap: 12,
    backgroundColor: colors.surface, borderRadius: 12,
    padding: 14, borderWidth: 1, borderColor: colors.border,
  },
  goalDot: { width: 8, height: 8, borderRadius: 4, backgroundColor: colors.primary },
  goalInfo: { flex: 1 },
  goalTitle: { fontFamily: fonts.bodyMedium, fontSize: 14, color: colors.textPrimary },
  goalCategory: { fontFamily: fonts.body, fontSize: 12, color: colors.textMuted, marginTop: 2 },
  goalStatus: {
    paddingVertical: 3, paddingHorizontal: 9,
    backgroundColor: colors.primaryMuted, borderRadius: 20,
    borderWidth: 1, borderColor: colors.primaryBorder,
  },
  goalStatusText: { fontFamily: fonts.bodySemi, fontSize: 11, color: colors.primary },

  emptyCard: {
    backgroundColor: colors.surface, borderRadius: 14,
    padding: 28, alignItems: 'center', borderWidth: 1, borderColor: colors.border, gap: 6,
  },
  emptyIcon: { fontSize: 32, marginBottom: 4 },
  emptyText: { fontFamily: fonts.bodySemi, fontSize: 15, color: colors.textPrimary },
  emptyHint: { fontFamily: fonts.body, fontSize: 13, color: colors.textMuted, textAlign: 'center' },

  notifBanner: {
    backgroundColor: colors.primaryMuted, borderRadius: 10,
    padding: 12, borderWidth: 1, borderColor: colors.primaryBorder,
  },
  notifText: { fontFamily: fonts.body, fontSize: 13, color: colors.textPrimary },
});
