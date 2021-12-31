import { ArcwMode } from '../enums';
import { MonthName } from './month-name.interface';
import { DayName } from './day-name.interface';

export interface ArcwConfiguration {
  /**
   * List of post type to fetch
   */
  'post-type': string[];
  /**
   * List of categories to fetch
   */
  categories: string[];
  /**
   * Whether a link to archives should be added on calendar title
   */
  titleLink: boolean;
  /**
   * Mode of the calendar to render (Month or Year)
   */
  mode: ArcwMode;
  /**
   *
   */
  monthSelect: string;
  months: Record<number, MonthName>;
  days: Record<number, DayName>;
  weekStarts: number;
  postCount: boolean;
}
