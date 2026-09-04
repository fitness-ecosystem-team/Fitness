import React, { useCallback, useEffect, useState } from 'react';
import {
  View, Text, StyleSheet, ScrollView, TouchableOpacity,
  Modal, TextInput, ActivityIndicator, Alert, FlatList,
} from 'react-native';
import { SafeAreaView } from 'react-native-safe-area-context';
import client from '../../api/client';
import { colors } from '../../constants/colors';
import { fonts } from '../../constants/typography';

type Food = {
  id: number; name: string; brand?: string;
  calories_per_100g: number; protein_per_100g: number;
  carbs_per_100g: number; fat_per_100g: number;
};
type MealLog = {
  id: number; meal_type: string; quantity_g: number;
  calories: number; protein: number; carbs: number; fat: number;
  food: Food;
};
type DayTotals = { calories: number; protein: number; carbs: number; fat: number };

const MEAL_TYPES = ['breakfast', 'lunch', 'dinner', 'snack'];
const WATER_QUICK = [150, 250, 350, 500];
const CALORIE_GOAL = 2000;

const MACRO_CONFIG = [
  { key: 'protein', label: 'Protein', color: colors.info },
  { key: 'carbs',   label: 'Carbs',   color: colors.warning },
  { key: 'fat',     label: 'Fat',     color: colors.danger },
] as const;

export default function NutritionScreen() {
  const [totals, setTotals] = useState<DayTotals>({ calories: 0, protein: 0, carbs: 0, fat: 0 });
  const [logs, setLogs] = useState<MealLog[]>([]);
  const [waterMl, setWaterMl] = useState(0);
  const [loadingData, setLoadingData] = useState(true);

  const [mealModalOpen, setMealModalOpen] = useState(false);
  const [selectedMealType, setSelectedMealType] = useState('breakfast');
  const [foodSearch, setFoodSearch] = useState('');
  const [searchResults, setSearchResults] = useState<Food[]>([]);
  const [selectedFood, setSelectedFood] = useState<Food | null>(null);
  const [quantity, setQuantity] = useState('100');
  const [searchLoading, setSearchLoading] = useState(false);
  const [logging, setLogging] = useState(false);

  const fetchData = useCallback(async () => {
    try {
      const [mealsRes, waterRes] = await Promise.all([
        client.get('/nutrition/meals/today'),
        client.get('/nutrition/water/today'),
      ]);
      setTotals(mealsRes.data.totals);
      setLogs(mealsRes.data.logs);
      setWaterMl(waterRes.data.total_ml);
    } catch {}
    setLoadingData(false);
  }, []);

  useEffect(() => { fetchData(); }, [fetchData]);

  const searchFoods = async (q: string) => {
    setFoodSearch(q);
    if (q.length < 2) { setSearchResults([]); return; }
    setSearchLoading(true);
    try {
      const res = await client.get(`/nutrition/foods?search=${q}`);
      setSearchResults(res.data);
    } catch {}
    setSearchLoading(false);
  };

  const closeModal = () => {
    setMealModalOpen(false);
    setSelectedFood(null);
    setFoodSearch('');
    setSearchResults([]);
    setQuantity('100');
  };

  const logMeal = async () => {
    if (!selectedFood) { Alert.alert('Select a food first'); return; }
    if (!quantity || isNaN(Number(quantity))) { Alert.alert('Enter a valid quantity'); return; }
    setLogging(true);
    try {
      await client.post('/nutrition/meals', {
        food_id: selectedFood.id,
        meal_type: selectedMealType,
        quantity_g: Number(quantity),
      });
      closeModal();
      fetchData();
    } catch (e: any) {
      Alert.alert('Error', e.response?.data?.message ?? 'Failed to log meal');
    }
    setLogging(false);
  };

  const logWater = async (ml: number) => {
    try {
      await client.post('/nutrition/water', { amount_ml: ml });
      setWaterMl((p) => p + ml);
    } catch {}
  };

  const deleteLog = async (id: number) => {
    try {
      await client.delete(`/nutrition/meals/${id}`);
      fetchData();
    } catch {}
  };

  const calorieProgress = Math.min((totals.calories / CALORIE_GOAL) * 100, 100);
  const remaining = Math.max(CALORIE_GOAL - Math.round(totals.calories), 0);

  if (loadingData) {
    return (
      <View style={styles.center}>
        <ActivityIndicator color={colors.primary} size="large" />
      </View>
    );
  }

  return (
    <SafeAreaView style={styles.container}>
      <ScrollView contentContainerStyle={styles.scroll} showsVerticalScrollIndicator={false}>

        {/* Header */}
        <View style={styles.header}>
          <View style={styles.sectionAccentRow}>
            <View style={styles.sectionAccent} />
            <Text style={styles.pageTitle}>Nutrition</Text>
          </View>
          <Text style={styles.dateLabel}>Today</Text>
        </View>

        {/* Calorie card */}
        <View style={styles.calorieCard}>
          <View style={styles.calorieTop}>
            <View>
              <Text style={styles.calorieValue}>{Math.round(totals.calories)}</Text>
              <Text style={styles.calorieSubLabel}>kcal eaten</Text>
            </View>
            <View style={styles.calorieSeparator} />
            <View style={styles.calorieRight}>
              <Text style={[styles.calorieValue, { color: remaining === 0 ? colors.success : colors.primary }]}>
                {remaining}
              </Text>
              <Text style={styles.calorieSubLabel}>kcal remaining</Text>
            </View>
          </View>
          <View style={styles.progressTrack}>
            <View style={[styles.progressFill, { width: `${calorieProgress}%` as any }]} />
          </View>
          <Text style={styles.progressGoalText}>Goal: {CALORIE_GOAL} kcal</Text>
        </View>

        {/* Macros */}
        <View style={styles.macrosRow}>
          {MACRO_CONFIG.map(({ key, label, color }) => (
            <View key={key} style={[styles.macroCard, { borderTopColor: color }]}>
              <Text style={[styles.macroValue, { color }]}>
                {Math.round(Number(totals[key]))}g
              </Text>
              <Text style={styles.macroLabel}>{label}</Text>
            </View>
          ))}
        </View>

        {/* Water */}
        <View style={styles.card}>
          <View style={styles.cardHeader}>
            <View style={styles.cardTitleRow}>
              <Text style={styles.cardIcon}>💧</Text>
              <Text style={styles.cardTitle}>Water</Text>
            </View>
            <View style={styles.waterTotalBadge}>
              <Text style={styles.waterTotalText}>{(waterMl / 1000).toFixed(1)} L</Text>
            </View>
          </View>
          <View style={styles.waterBtns}>
            {WATER_QUICK.map((ml) => (
              <TouchableOpacity key={ml} style={styles.waterBtn} onPress={() => logWater(ml)} activeOpacity={0.7}>
                <Text style={styles.waterBtnText}>+{ml}ml</Text>
              </TouchableOpacity>
            ))}
          </View>
        </View>

        {/* Meal sections */}
        {MEAL_TYPES.map((type) => {
          const typeLogs = logs.filter((l) => l.meal_type === type);
          const typeCalories = typeLogs.reduce((s, l) => s + Number(l.calories), 0);
          return (
            <View key={type} style={styles.card}>
              <View style={styles.cardHeader}>
                <Text style={styles.cardTitle}>{capitalize(type)}</Text>
                <Text style={styles.mealKcal}>
                  {typeCalories > 0 ? `${Math.round(typeCalories)} kcal` : '0 kcal'}
                </Text>
              </View>

              {typeLogs.map((log) => (
                <View key={log.id} style={styles.logRow}>
                  <View style={styles.logDot} />
                  <View style={styles.logInfo}>
                    <Text style={styles.logName}>{log.food.name}</Text>
                    <Text style={styles.logMeta}>{log.quantity_g}g · {Math.round(Number(log.calories))} kcal</Text>
                  </View>
                  <TouchableOpacity onPress={() => deleteLog(log.id)} hitSlop={{ top: 8, bottom: 8, left: 8, right: 8 }}>
                    <Text style={styles.deleteBtn}>✕</Text>
                  </TouchableOpacity>
                </View>
              ))}

              <TouchableOpacity
                style={styles.addFoodBtn}
                onPress={() => { setSelectedMealType(type); setMealModalOpen(true); }}
                activeOpacity={0.7}
              >
                <Text style={styles.addFoodText}>+ Add food</Text>
              </TouchableOpacity>
            </View>
          );
        })}
      </ScrollView>

      {/* Add Meal Modal */}
      <Modal visible={mealModalOpen} animationType="slide" transparent>
        <View style={styles.modalOverlay}>
          <View style={styles.modalSheet}>
            {/* Handle */}
            <View style={styles.modalHandle} />

            <View style={styles.modalHeader}>
              <Text style={styles.modalTitle}>Add to {capitalize(selectedMealType)}</Text>
              <TouchableOpacity onPress={closeModal} hitSlop={{ top: 8, bottom: 8, left: 8, right: 8 }}>
                <Text style={styles.modalClose}>✕</Text>
              </TouchableOpacity>
            </View>

            {!selectedFood ? (
              <>
                <TextInput
                  style={styles.searchInput}
                  placeholder="Search food..."
                  placeholderTextColor={colors.textMuted}
                  value={foodSearch}
                  onChangeText={searchFoods}
                  autoFocus
                />
                {searchLoading && <ActivityIndicator color={colors.primary} style={{ marginTop: 12 }} />}
                <FlatList
                  data={searchResults}
                  keyExtractor={(item) => String(item.id)}
                  style={{ maxHeight: 300 }}
                  renderItem={({ item }) => (
                    <TouchableOpacity style={styles.foodRow} onPress={() => setSelectedFood(item)} activeOpacity={0.7}>
                      <View style={styles.foodInfo}>
                        <Text style={styles.foodName}>{item.name}</Text>
                        {item.brand && <Text style={styles.foodBrand}>{item.brand}</Text>}
                      </View>
                      <View style={styles.foodCalBadge}>
                        <Text style={styles.foodCalText}>{item.calories_per_100g} kcal</Text>
                        <Text style={styles.foodCalUnit}>/100g</Text>
                      </View>
                    </TouchableOpacity>
                  )}
                  ListEmptyComponent={
                    foodSearch.length > 1 && !searchLoading ? (
                      <Text style={styles.emptySearch}>No foods found. Add one via the API.</Text>
                    ) : null
                  }
                />
              </>
            ) : (
              <View style={styles.quantitySection}>
                <View style={styles.selectedFoodHeader}>
                  <Text style={styles.selectedFoodName}>{selectedFood.name}</Text>
                  {selectedFood.brand && <Text style={styles.selectedFoodBrand}>{selectedFood.brand}</Text>}
                </View>

                <View style={styles.quantityField}>
                  <Text style={styles.quantityLabel}>Quantity</Text>
                  <View style={styles.quantityInputRow}>
                    <TextInput
                      style={styles.quantityInput}
                      value={quantity}
                      onChangeText={setQuantity}
                      keyboardType="numeric"
                    />
                    <View style={styles.quantityUnit}>
                      <Text style={styles.quantityUnitText}>g</Text>
                    </View>
                  </View>
                </View>

                {/* Live macro preview */}
                <View style={styles.previewRow}>
                  {(['calories', 'protein', 'carbs', 'fat'] as const).map((m) => {
                    const key = `${m}_per_100g` as keyof Food;
                    const val = ((selectedFood[key] as number) * Number(quantity || 0)) / 100;
                    const color = m === 'calories' ? colors.primary : m === 'protein' ? colors.info : m === 'carbs' ? colors.warning : colors.danger;
                    return (
                      <View key={m} style={styles.previewItem}>
                        <Text style={[styles.previewValue, { color }]}>{Math.round(val)}</Text>
                        <Text style={styles.previewUnit}>{m === 'calories' ? 'kcal' : 'g'}</Text>
                        <Text style={styles.previewKey}>{capitalize(m)}</Text>
                      </View>
                    );
                  })}
                </View>

                <View style={styles.modalActions}>
                  <TouchableOpacity style={styles.backBtn} onPress={() => setSelectedFood(null)} activeOpacity={0.7}>
                    <Text style={styles.backBtnText}>← Back</Text>
                  </TouchableOpacity>
                  <TouchableOpacity
                    style={[styles.logBtn, logging && styles.logBtnDisabled]}
                    onPress={logMeal}
                    disabled={logging}
                    activeOpacity={0.85}
                  >
                    {logging
                      ? <ActivityIndicator color="#fff" size="small" />
                      : <Text style={styles.logBtnText}>Log Meal</Text>}
                  </TouchableOpacity>
                </View>
              </View>
            )}
          </View>
        </View>
      </Modal>
    </SafeAreaView>
  );
}

function capitalize(s: string) { return s.charAt(0).toUpperCase() + s.slice(1); }

const styles = StyleSheet.create({
  container: { flex: 1, backgroundColor: colors.background },
  center: { flex: 1, justifyContent: 'center', alignItems: 'center', backgroundColor: colors.background },
  scroll: { padding: 20, paddingBottom: 32, gap: 14 },

  header: { flexDirection: 'row', justifyContent: 'space-between', alignItems: 'center' },
  sectionAccentRow: { flexDirection: 'row', alignItems: 'center', gap: 8 },
  sectionAccent: { width: 3, height: 18, backgroundColor: colors.primary, borderRadius: 2 },
  pageTitle: { fontFamily: fonts.heading, fontSize: 22, color: colors.textPrimary },
  dateLabel: { fontFamily: fonts.body, fontSize: 13, color: colors.textSecondary },

  calorieCard: {
    backgroundColor: colors.surface, borderRadius: 14,
    padding: 18, borderWidth: 1, borderColor: colors.border, gap: 14,
  },
  calorieTop: { flexDirection: 'row', alignItems: 'center', justifyContent: 'space-between' },
  calorieValue: { fontFamily: fonts.heading, fontSize: 32, color: colors.textPrimary },
  calorieSubLabel: { fontFamily: fonts.body, fontSize: 12, color: colors.textSecondary, marginTop: 2 },
  calorieSeparator: { width: 1, height: 40, backgroundColor: colors.border },
  calorieRight: { alignItems: 'flex-end' },
  progressTrack: { height: 5, backgroundColor: colors.surfaceRaised, borderRadius: 3 },
  progressFill: { height: 5, backgroundColor: colors.primary, borderRadius: 3 },
  progressGoalText: { fontFamily: fonts.body, fontSize: 11, color: colors.textMuted },

  macrosRow: { flexDirection: 'row', gap: 10 },
  macroCard: {
    flex: 1, backgroundColor: colors.surface, borderRadius: 12,
    padding: 14, borderWidth: 1, borderColor: colors.border,
    borderTopWidth: 2, alignItems: 'center', gap: 4,
  },
  macroValue: { fontFamily: fonts.heading, fontSize: 18 },
  macroLabel: { fontFamily: fonts.body, fontSize: 11, color: colors.textSecondary },

  card: {
    backgroundColor: colors.surface, borderRadius: 14,
    padding: 16, borderWidth: 1, borderColor: colors.border, gap: 12,
  },
  cardHeader: { flexDirection: 'row', justifyContent: 'space-between', alignItems: 'center' },
  cardTitleRow: { flexDirection: 'row', alignItems: 'center', gap: 6 },
  cardIcon: { fontSize: 16 },
  cardTitle: { fontFamily: fonts.headingSemi, fontSize: 15, color: colors.textPrimary },
  waterTotalBadge: {
    backgroundColor: colors.primaryMuted, borderRadius: 20,
    paddingVertical: 4, paddingHorizontal: 10,
    borderWidth: 1, borderColor: colors.primaryBorder,
  },
  waterTotalText: { fontFamily: fonts.heading, fontSize: 14, color: colors.primary },
  waterBtns: { flexDirection: 'row', gap: 8 },
  waterBtn: {
    flex: 1, paddingVertical: 10, alignItems: 'center',
    backgroundColor: colors.surfaceRaised, borderRadius: 9,
    borderWidth: 1, borderColor: colors.border,
  },
  waterBtnText: { fontFamily: fonts.bodySemi, fontSize: 12, color: colors.textSecondary },

  mealKcal: { fontFamily: fonts.body, fontSize: 13, color: colors.textMuted },
  logRow: {
    flexDirection: 'row', alignItems: 'center', gap: 10,
    paddingTop: 10, borderTopWidth: 1, borderTopColor: colors.border,
  },
  logDot: { width: 6, height: 6, borderRadius: 3, backgroundColor: colors.primary },
  logInfo: { flex: 1 },
  logName: { fontFamily: fonts.bodyMedium, fontSize: 14, color: colors.textPrimary },
  logMeta: { fontFamily: fonts.body, fontSize: 12, color: colors.textMuted, marginTop: 2 },
  deleteBtn: { color: colors.textMuted, fontSize: 14 },
  addFoodBtn: { paddingTop: 4 },
  addFoodText: { fontFamily: fonts.bodySemi, fontSize: 13, color: colors.primary },

  // Modal
  modalOverlay: { flex: 1, backgroundColor: colors.overlay, justifyContent: 'flex-end' },
  modalSheet: {
    backgroundColor: colors.surface, borderTopLeftRadius: 24, borderTopRightRadius: 24,
    padding: 24, maxHeight: '88%', gap: 16,
  },
  modalHandle: {
    width: 36, height: 4, backgroundColor: colors.surfaceRaised,
    borderRadius: 2, alignSelf: 'center', marginBottom: 4,
  },
  modalHeader: { flexDirection: 'row', justifyContent: 'space-between', alignItems: 'center' },
  modalTitle: { fontFamily: fonts.heading, fontSize: 18, color: colors.textPrimary },
  modalClose: { fontSize: 18, color: colors.textMuted, padding: 4 },
  searchInput: {
    backgroundColor: colors.surfaceRaised, borderWidth: 1, borderColor: colors.border,
    borderRadius: 10, paddingHorizontal: 14, paddingVertical: 13,
    color: colors.textPrimary, fontFamily: fonts.body, fontSize: 15,
  },
  foodRow: {
    flexDirection: 'row', justifyContent: 'space-between', alignItems: 'center',
    paddingVertical: 13, borderBottomWidth: 1, borderBottomColor: colors.border,
  },
  foodInfo: { flex: 1 },
  foodName: { fontFamily: fonts.bodyMedium, fontSize: 14, color: colors.textPrimary },
  foodBrand: { fontFamily: fonts.body, fontSize: 12, color: colors.textMuted, marginTop: 2 },
  foodCalBadge: { alignItems: 'flex-end' },
  foodCalText: { fontFamily: fonts.bodySemi, fontSize: 13, color: colors.primary },
  foodCalUnit: { fontFamily: fonts.body, fontSize: 10, color: colors.textMuted },
  emptySearch: { fontFamily: fonts.body, color: colors.textMuted, textAlign: 'center', paddingVertical: 24, fontSize: 14 },

  quantitySection: { gap: 16 },
  selectedFoodHeader: { gap: 2 },
  selectedFoodName: { fontFamily: fonts.heading, fontSize: 18, color: colors.textPrimary },
  selectedFoodBrand: { fontFamily: fonts.body, fontSize: 13, color: colors.textMuted },
  quantityField: { gap: 8 },
  quantityLabel: { fontFamily: fonts.bodySemi, fontSize: 13, color: colors.textSecondary },
  quantityInputRow: { flexDirection: 'row', gap: 8 },
  quantityInput: {
    flex: 1, backgroundColor: colors.surfaceRaised, borderWidth: 1, borderColor: colors.border,
    borderRadius: 10, paddingHorizontal: 14, paddingVertical: 13,
    color: colors.textPrimary, fontFamily: fonts.heading, fontSize: 20,
  },
  quantityUnit: {
    width: 48, justifyContent: 'center', alignItems: 'center',
    backgroundColor: colors.surfaceRaised, borderRadius: 10,
    borderWidth: 1, borderColor: colors.border,
  },
  quantityUnitText: { fontFamily: fonts.bodySemi, color: colors.textSecondary, fontSize: 15 },
  previewRow: {
    flexDirection: 'row', justifyContent: 'space-around',
    backgroundColor: colors.surfaceRaised, borderRadius: 12, padding: 16,
  },
  previewItem: { alignItems: 'center', gap: 2 },
  previewValue: { fontFamily: fonts.heading, fontSize: 20 },
  previewUnit: { fontFamily: fonts.body, fontSize: 10, color: colors.textMuted },
  previewKey: { fontFamily: fonts.body, fontSize: 11, color: colors.textSecondary },
  modalActions: { flexDirection: 'row', gap: 10 },
  backBtn: {
    flex: 1, paddingVertical: 14, alignItems: 'center',
    backgroundColor: colors.surfaceRaised, borderRadius: 10,
    borderWidth: 1, borderColor: colors.border,
  },
  backBtnText: { fontFamily: fonts.bodySemi, color: colors.textSecondary, fontSize: 14 },
  logBtn: {
    flex: 2, paddingVertical: 14, alignItems: 'center',
    backgroundColor: colors.primary, borderRadius: 10,
  },
  logBtnDisabled: { opacity: 0.6 },
  logBtnText: { fontFamily: fonts.bodySemi, color: '#fff', fontSize: 14 },
});
