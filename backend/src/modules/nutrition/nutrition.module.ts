import { Module } from '@nestjs/common';

/**
 * Integration boundary owned by the Nutrition Module Backend workstream.
 * Nutrition may consume exported Core services; Core must never import Nutrition.
 */
@Module({})
export class NutritionModule {}
