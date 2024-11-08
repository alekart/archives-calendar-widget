import { Modes, MonthConfig } from '../enums';

export interface ArcwConfig {
  mode: Modes;
  firstMonth: MonthConfig;
  categories: {
    [k: string]: string[];
  }
  postTypes: string[];
  theme: string;
  [k: string]: unknown;
}

