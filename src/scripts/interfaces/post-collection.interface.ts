import { Post } from './post.interface';

export type PostCollection = {
  yearsOrdered: string[];
  years: Record<string, YearPosts>;
};

export interface YearPosts {
  year: number;
  monthsOrdered: string[]
  months: Record<string, MonthPosts>;
}

export interface MonthPosts {
  count: number;
  days: Record<string, DayPosts>;
}

export interface DayPosts {
  count: number;
  posts: Post[];
}
