import { groupBy, orderBy } from 'lodash';
import { ArcwMode } from './enums';
import { ArcwConfiguration, DayName, Post } from './interfaces';
import ArcwHelpers from './templates';
import { MonthPosts, PostCollection, YearPosts } from './interfaces/post-collection.interface';

export default class ArcwCalendar {
  /**
   * Raw fetched posts
   */
  private rawPosts: Post[] = [];

  /**
   * Posts collection
   */
  private posts: PostCollection;
  /**
   * Calendar container element
   */

  private container: HTMLElement;
  /**
   * Calendar configuration
   */

  private configuration: ArcwConfiguration;
  /**
   * The error message container
   */

  private errorContainer: HTMLElement;
  /**
   * WordPress ajax url to use for js wp requests
   */

  ajaxUrl: string;

  constructor(element: HTMLElement, ajaxUrl: string) {
    this.container = element;
    this.errorContainer = element.querySelector('.arcw-error');
    this.ajaxUrl = ajaxUrl;
    this.getConfigurationFromAttributes();
    console.log('ARCW configuration: ', this.configuration);
    this.getPosts().then((posts) => {
      console.log('plop');
      this.posts = posts;
      this.buildCalendar();
    }).catch((e) => {
      console.error('ARCW: posts could not be loaded.');
      console.error(e);
    });

    // #region date hover feature
    // TODO: this is a feature to add hover on date
    const viewContainer = this.container.querySelector('.arcw-view');
    viewContainer.addEventListener('mouseover', (event) => {
      const targetElem: HTMLElement = <HTMLElement>event.target;
      if (targetElem.dataset.date) {
        const date = new Date(targetElem.dataset.date);
        console.log(this.getPostsFor(date.getFullYear(), date.getMonth() + 1, date.getDate()));
      }
    }, false);
    // #endregion date hover
  }

  /**
   * Transform an object into an url serialized string
   * @param data {{}}
   * @returns {string|null}
   */
  static serializeJSON(data: { [k: string]: string }): string {
    if (!data) {
      return null;
    }
    return Object.keys(data).map((k) => `${encodeURIComponent(k)}=${encodeURIComponent(data[k])}`).join('&');
  }

  /**
   * Promised Http request
   * @param url {string}
   * @param params {[k: string]: any}
   * @param method {'POST'|'GET'}
   */
  static request<T = any>(url: string, params: { [k: string]: any }, method = 'POST'): Promise<T> {
    return new Promise((resolve, reject) => {
      const xhr = new XMLHttpRequest();
      const rejectError = () => reject(new Error(xhr.statusText));

      xhr.open(method, url);
      xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');

      xhr.onload = () => {
        if (xhr.status >= 200 && xhr.status < 300) {
          resolve(JSON.parse(xhr.response));
        } else {
          rejectError();
        }
      };
      xhr.onerror = rejectError;
      xhr.send(ArcwCalendar.serializeJSON(params));
    });
  }

  /**
   * Group post by years, month then days. This recursive collection will allow displaying
   * yearly and monthly calendar.
   */
  static groupPosts(posts: Post[]): PostCollection {
    // Group posts by year
    const byYear = groupBy(posts, (post) => post.date.slice(0, 4));
    const postCollection: PostCollection = {
      yearsOrdered: [],
      years: {},
    };
    // For each year group
    Object.entries(byYear).forEach(([year, postList]) => {
      const months = ArcwCalendar.groupPostsByMonth(postList);
      postCollection.yearsOrdered.push(year);
      postCollection.years[year] = {
        year: parseInt(year, 10),
        monthsOrdered: Object.keys(months).sort(),
        months,
      };
    });
    postCollection.yearsOrdered.sort();
    return postCollection;
  }

  static groupPostsByMonth(posts: Post[]): YearPosts['months'] {
    const months: YearPosts['months'] = {};
    const byMonth = groupBy(posts, (post: Post) => parseInt(post.date.slice(5, 7), 10));
    for (const [month, postList] of Object.entries(byMonth)) {
      months[month] = {
        count: postList.length,
        days: ArcwCalendar.groupPostsByDay(postList),
      };
    }
    return months;
  }

  static groupPostsByDay(posts: Post[]): MonthPosts['days'] {
    const days: MonthPosts['days'] = {};
    const dayGroups = groupBy(posts, (post: Post) => parseInt(post.date.slice(8, 10), 10));
    // eslint-disable-next-line no-restricted-syntax
    for (const [day, postList] of Object.entries(dayGroups)) {
      days[day] = {
        count: postList.length,
        posts: postList,
      };
    }
    return days;
  }

  /**
   * Retrieve calendar configuration from element attribute
   */
  private getConfigurationFromAttributes() {
    try {
      this.configuration = JSON.parse(this.container?.dataset?.configuration);
    } catch (e) {
      console.error('ARCW: configuration could not be loaded');
      this.displayError();
    }
  }

  /**
   * Request archive posts with specific calendar configuration
   */
  private getPosts(): Promise<PostCollection> {
    const reqParams = {
      action: 'arcwGetPosts',
      'post-type': this.configuration['post-type'],
      categories: this.configuration.categories || [],
    };
    return ArcwCalendar.request(this.ajaxUrl, reqParams)
      .then((posts: Post[]) => {
        this.rawPosts = posts;
        return ArcwCalendar.groupPosts(posts);
      });
  }

  /**
   * Add class on calendar container to make error message visible.
   */
  private displayError() {
    this.container.classList.add('.arcw--has-error');
  }

  /**
   * Remove class on calendar container to hide error message.
   */
  private hideError() {
    this.container.classList.remove('.arcw--has-error');
  }

  getPostsFor(year: number | string, month: number | string, day?: number | string): Post[] {
    const monthPosts = this.posts?.years[year.toString()]?.months[month.toString()];
    if (!monthPosts) {
      return [];
    }
    if (day) {
      return monthPosts?.days[day.toString()]?.posts || [];
    }
    const posts: Post[] = [];
    for (const [, daysPosts] of Object.entries(monthPosts.days)) {
      posts.push(...daysPosts.posts);
    }
    return orderBy(posts, ['date'], ['asc']);
  }

  getMonthName(number: number, short = false): string {
    const month = this.configuration.months[number];
    return short ? month.short : month.full;
  }

  buildCalendar() {
    if (this.configuration.mode === ArcwMode.Year) {
      // this.buildMonthCalendar();
      this.buildYearCalendar();
    } else {
      // this.buildMonthCalendar();
      this.buildYearCalendar();
    }
  }

  generateMonthGrid(year: number, month: number, daysWithPosts?: MonthPosts['days']): HTMLElement {
    const gridContainer = ArcwHelpers.createElement('div', '', ['arcw-view__grid', 'arcw-view__grid--month']);
    const firstDay = new Date(year, month - 1).getDay();
    const numberOfDays = ArcwHelpers.getNumberOfDaysInMonth(month, year);
    let totalBoxes = 0;
    const zeroMonth = `0${month}`.slice(-2);

    // empty days in the beginning
    for (let i = 1; i !== firstDay; i += 1) {
      const element = ArcwHelpers.createElement('div', '', ['arcw-day', 'arcw-day--no-day']);
      gridContainer.appendChild(element);
      totalBoxes += 1;
      if (totalBoxes === 7) {
        totalBoxes = 0;
      }
    }

    for (let i = this.configuration.weekStarts; i <= numberOfDays; i += 1) {
      const day = i - this.configuration.weekStarts + 1;
      const posts: Post[] = daysWithPosts?.[day]?.posts;
      const element = ArcwHelpers.buildDayBox(new Date(year, month - 1, day), posts);
      gridContainer.appendChild(element);
      totalBoxes += 1;
    }

    // Empty days after the month
    for (let i = totalBoxes; i < 42; i += 1) {
      const element = ArcwHelpers.createElement('div', '', ['arcw-day', 'arcw-day--no-day']);
      gridContainer.appendChild(element);
    }

    return gridContainer;
  }

  buildMonthCalendar() {
    const daysContainer = this.container.querySelector('.arcw-weekdays');
    const pageContainer = this.container.querySelector('.arcw-view');

    for (let i = 0; i <= 6; i += 1) {
      const day: DayName['short'] = this.configuration.days[(i + this.configuration.weekStarts) % 7].short;
      const element = ArcwHelpers.createElement('div', day, ['arcw-weekday']);
      daysContainer.appendChild(element);
    }

    this.posts.yearsOrdered.forEach((year) => {
      const yearPosts = this.posts.years[year];
      yearPosts.monthsOrdered.forEach((month) => {
        const monthPosts = yearPosts.months[month];
        pageContainer.appendChild(
          this.generateMonthGrid(parseInt(year, 10), parseInt(month, 10), monthPosts.days),
        );
      });
    });
  }

  buildYearCalendar() {
    const pageContainer = this.container.querySelector('.arcw-view');
    this.posts.yearsOrdered.forEach((year) => {
      pageContainer.appendChild(this.generateYearGrid(parseInt(year, 10)));
    });
  }

  private generateYearGrid(year: number): HTMLElement {
    const gridContainer = ArcwHelpers.createElement('div', '', ['arcw-view__grid', 'arcw-view__grid--year']);
    for (let i = 1; i <= 12; i += 1) {
      const posts = this.getPostsFor(year, i);
      const date = new Date(`${year}-${i}-1`);
      console.log(year, date, posts);
      gridContainer.appendChild(ArcwHelpers.buildMonthBox(date, this.configuration, posts));
    }
    return gridContainer;
  }

  createYearMenu(): HTMLSelectElement {
    const select = ArcwHelpers.createElement('select', null) as HTMLSelectElement;
    this.posts.yearsOrdered.forEach((year) => {
      const option = ArcwHelpers.createElement('option', year.toString(), [], { value: year });
      select.appendChild(option);
    });
    return select;
  }
}
