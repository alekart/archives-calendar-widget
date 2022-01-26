import { orderBy } from 'lodash';
import { ArcwMode } from './enums';
import Helpers from './helpers';
import { ArcwConfiguration, DayName, MonthPosts, Post, PostCollection } from './interfaces';

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

  private elements: {
    nav: Element;
    navNext: Element;
    navPrev: Element;
    navLink: Element;
    navSelect: Element;
  };

  private templates: Record<string, HTMLTemplateElement>;

  /**
   * WordPress ajax url to use for js wp requests
   */
  ajaxUrl: string;

  constructor(element: HTMLElement, ajaxUrl: string, private devMode = false) {
    const nav = element.querySelector('[data-arcw-nav]');
    this.ajaxUrl = ajaxUrl;
    this.templates = {
      dayEmpty: document.querySelector('#arcw-day-empty'),
      dayFilled: document.querySelector('#arcw-day-filled'),
      monthEmpty: document.querySelector('#arcw-month-empty'),
      monthFilled: document.querySelector('#arcw-month-filled'),
    };
    this.container = element;
    this.elements = {
      nav,
      navPrev: nav.querySelector('[data-arcw-prev]'),
      navNext: nav.querySelector('[data-arcw-next]'),
      navLink: nav.querySelector('[data-arcw-link]'),
      navSelect: nav.querySelector('[data-arcw-select]'),
    };
    this.getConfigurationFromAttributes();
    this.prepareNav();
    this.getPosts().then((posts) => {
      if (posts) {
        this.posts = posts;
        this.buildCalendar();
      }
    });
    this.addEventListeners();
  }

  private addEventListeners() {
    // #region date hover feature
    // TODO: this is a feature to add hover on date
    const viewContainer = this.container.querySelector('.arcw-view');
    viewContainer.addEventListener('mouseover', (event) => {
      const targetElem: HTMLElement = <HTMLElement>event.target;
      if (targetElem.dataset.date) {
        const date = new Date(targetElem.dataset.date);
      }
    }, false);
    // #endregion date hover

    this.elements.nav.addEventListener('click', (event: any) => {
      console.log(event.target);
    });

    // selectMenu.addEventListener('change', (event: any) => {
    //   console.log(event.target.value);
    //   console.log(event);
    // });
  }

  /**
   * Retrieve calendar configuration from element attribute
   */
  private getConfigurationFromAttributes() {
    try {
      this.configuration = JSON.parse(this.container?.dataset?.configuration);
    } catch (e) {
      console.error('ARCW: configuration could not be loaded');
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
    return new Promise<PostCollection>((resolve) => {
      Helpers.request(this.ajaxUrl, reqParams, this.devMode ? 'GET' : 'POST')
        .then((posts: Post[]) => {
          this.rawPosts = posts;
          resolve(Helpers.groupPosts(posts));
        }).catch((e) => {
          console.error('ARCW: posts could not be loaded.');
          console.error(e);
          resolve(null);
        });
    });
  }

  private getPostsFor(year: number | string, month: number | string, day?: number | string): Post[] {
    const monthPosts = this.posts?.years[year.toString()]?.months[month.toString()];
    if (!monthPosts) {
      return [];
    }
    if (day) {
      return monthPosts?.days[day.toString()]?.posts || [];
    }
    const posts: Post[] = [];
    Object.keys(monthPosts.days).forEach((key) => {
      posts.push(...monthPosts.days[key].posts);
    });
    return orderBy(posts, ['date'], ['asc']);
  }

  /**
   * Get the translated month name
   * @param month from 1 to 12
   * @param short when true will return short name
   */
  private getMonthName(month: number | string, short = false): string {
    const monthNum = typeof month === 'string'
      ? parseInt(month, 10) - 1
      : month - 1;
    const monthI18n = this.configuration.months[monthNum];
    return short ? monthI18n.short : monthI18n.full;
  }

  private buildCalendar() {
    if (this.configuration.mode === ArcwMode.Year) {
      this.buildYearCalendar();
    } else {
      this.buildMonthCalendar();
    }
  }

  private buildMonthCalendar() {
    const daysContainer = this.container.querySelector('.arcw-weekdays');
    const pageContainer = this.container.querySelector('.arcw-view');

    for (let i = 0; i <= 6; i += 1) {
      const day: DayName['short'] = this.configuration.days[(i + this.configuration.weekStarts) % 7].short;
      const element = Helpers.createElement('div', day, ['arcw-weekday']);
      daysContainer.appendChild(element);
    }

    this.posts.yearsOrdered.forEach((year) => {
      const yearPosts = this.posts.years[year];
      yearPosts.monthsOrdered.forEach((month, index) => {
        const monthPosts = yearPosts.months[month];
        const label = this.getMonthSelectionLabel(month, year);
        this.elements.navSelect.appendChild(Helpers.getSelectOption(label, `${index}-${year}-${month}`));
        if (index === 0) {
          pageContainer.appendChild(
            this.generateMonthGrid(parseInt(year, 10), parseInt(month, 10), monthPosts.days),
          );
        }
      });
    });
  }

  private buildYearCalendar() {
    const pageContainer = this.container.querySelector('.arcw-view');
    this.posts.yearsOrdered.forEach((year) => {
      pageContainer.appendChild(this.generateYearGrid(parseInt(year, 10)));
      this.elements.navSelect.appendChild(Helpers.getSelectOption(year, year));
    });
  }

  private generateMonthGrid(year: number, month: number, daysWithPosts?: MonthPosts['days']): HTMLElement {
    const gridContainer = Helpers.createElement('div', '', ['arcw-view__grid', 'arcw-view__grid--month']);
    const noDayElement = Helpers.createElement('div', '', ['arcw-box', 'arcw-box--no-day']);
    const firstDay = new Date(year, month - 1).getDay();
    const numberOfDays = Helpers.getNumberOfDaysInMonth(month, year);
    let totalBoxes = 0;

    // empty days in the beginning
    for (let i = 1; i !== firstDay; i += 1) {
      gridContainer.appendChild(noDayElement.cloneNode());
      totalBoxes += 1;
      if (totalBoxes === 7) {
        totalBoxes = 0;
      }
    }

    for (let i = this.configuration.weekStarts; i <= numberOfDays; i += 1) {
      const day = i - this.configuration.weekStarts + 1;
      const posts: Post[] = daysWithPosts?.[day]?.posts || [];
      const template = posts?.length ? this.templates.dayFilled : this.templates.dayEmpty;
      this.addDayBox(new Date(year, month - 1, day), posts, template, gridContainer);
      totalBoxes += 1;
    }

    // Empty days after the month
    for (let i = totalBoxes; i < 42; i += 1) {
      gridContainer.appendChild(noDayElement.cloneNode());
    }

    return gridContainer;
  }

  private generateYearGrid(year: number): HTMLElement {
    const gridContainer = Helpers.createElement('div', '', ['arcw-view__grid', 'arcw-view__grid--year']);
    for (let i = 1; i <= 12; i += 1) {
      const posts = this.getPostsFor(year, i);
      const date = new Date(`${year}-${i}-1`);
      const template = posts.length ? this.templates.monthFilled : this.templates.monthEmpty;
      this.addMonthBox(date, posts, template, gridContainer);
    }
    return gridContainer;
  }

  /**
   * Return label for the navigation select menu with the month name and year
   * if archive year is different from current.
   */
  private getMonthSelectionLabel(month: string, year: string): string {
    const currentYear = new Date().getFullYear();
    const yearLabel = year === currentYear.toString() ? '' : ` | ${year}`;
    return this.getMonthName(month) + yearLabel;
  }

  private addMonthBox(
    date: Date | null,
    posts: Post[],
    template: HTMLTemplateElement,
    container: Element,
  ) {
    const isoDate = date.toISOString();
    const month = date.getMonth();
    const monthLong = this.configuration?.months?.[month]?.full;
    const monthShort = this.configuration?.months?.[month]?.short;

    const box = Helpers.cloneTemplateToElement(template);
    if (posts?.length) {
      box.querySelector('[data-number]').textContent = `${posts.length}`;
      box.querySelector('[data-label]').textContent = 'Posts';
      box.querySelector('[data-name]').textContent = monthShort;
    } else {
      box.querySelector('[data-name]').textContent = monthShort;
    }
    Helpers.setAttributes(box, {
      title: monthLong,
      'data-date': isoDate,
      href: posts[0]?.monthLink,
    });
    container.appendChild(box);
  }

  // eslint-disable-next-line class-methods-use-this
  private addDayBox(
    date: Date | null,
    posts: Post[],
    template: HTMLTemplateElement,
    container: Element,
  ) {
    const isoDate = date.toISOString();
    const box = Helpers.cloneTemplateToElement(template);
    box.innerHTML = `${date.getDate()}`;
    if (posts.length) {
      Helpers.setAttributes(box, {
        href: posts[0]?.dayLink,
        'data-date': isoDate,
      });
    }
    container.appendChild(box);
  }

  private prepareNav() {
    this.elements.navPrev.textContent = '<';
    this.elements.navNext.textContent = '>';
  }
}
