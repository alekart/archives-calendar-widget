import { groupBy } from 'lodash';
import { MonthPosts, Post, PostCollection, YearPosts } from './interfaces';

export default class Helpers {
  /**
   * Return the number of days for specified month.
   * Takes into account leap years for the month of February.
   */
  static getNumberOfDaysInMonth(month: number, year?: number): number {
    if (month === 2 && !year) {
      throw new Error('The year is mandatory to get number of days for february (2)');
    }
    return new Date(year || 1970, month, 0).getDate();
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
      xhr.send(Helpers.serializeJSON(params));
    });
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
    const years: string[] = [];
    // For each year group
    Object.entries(byYear).forEach(([year, postList]) => {
      const months = Helpers.groupPostsByMonth(postList);
      years.push(year);
      const monthsOrdered = Helpers.numbersToStrings(
        Helpers.sortNumbers(Helpers.stringsToNumbers(Object.keys(months))),
      );
      postCollection.years[year] = {
        year: parseInt(year, 10),
        monthsOrdered,
        months,
      };
    });
    const yearsOrdered = Helpers.numbersToStrings(
      Helpers.sortNumbers(Helpers.stringsToNumbers(years)),
    );
    postCollection.yearsOrdered = yearsOrdered;
    return postCollection;
  }

  static groupPostsByMonth(posts: Post[]): YearPosts['months'] {
    const months: YearPosts['months'] = {};
    const byMonth = groupBy(posts, (post: Post) => parseInt(post.date.slice(5, 7), 10));
    Object.keys(byMonth).forEach((month) => {
      const postList = byMonth[month];
      months[month] = {
        count: postList.length,
        days: Helpers.groupPostsByDay(postList),
      };
    });
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

  static sortNumbers(numbers: number[]): number[] {
    return [...numbers].sort((a, b) => a - b);
  }

  static stringsToNumbers(strings: string[]): number[] {
    return [...strings].map((n) => Number(n));
  }

  static numbersToStrings(numbers: number[]): string[] {
    return [...numbers].map((n) => n.toString());
  }

  /**
   * Create a div or span element with provided content, classes and attributes.
   */
  static createElement(
    tag: string,
    content?: string | HTMLElement,
    classes: string[] = [],
    attributes?: { [k: string]: string },
  ): HTMLElement {
    const element: HTMLElement = document.createElement(tag);
    if (classes.length) {
      element.classList.add(...classes);
    }
    if (attributes) {
      Helpers.setAttributes(element, attributes);
    }
    if (content && typeof content === 'string') {
      element.innerText = content;
    } else if (content && typeof content === 'object') {
      element.appendChild(content);
    }
    return element;
  }

  // eslint-disable-next-line class-methods-use-this
  static cloneTemplateToElement(template: HTMLTemplateElement): Element {
    const clone = <Element>template.content.cloneNode(true);
    return clone.firstElementChild;
  }

  static getSelectOption(label: string, value: string): HTMLOptionElement {
    return Helpers.createElement('option', label, [], { value }) as HTMLOptionElement;
  }

  static setAttributes<T extends Element>(element: T, attributes: Record<string, string>) {
    if (attributes) {
      Object.keys(attributes).forEach((attributeName) => {
        if (Object.prototype.hasOwnProperty.call(attributes, attributeName)) {
          element.setAttribute(attributeName, attributes[attributeName]);
        }
      });
    }
  }
}
