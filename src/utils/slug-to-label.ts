export function slugToText(slug: string): string {
  return slug
    .replace(/([-_])/g, ' ')
    .replace(/(\d)/g, ' $1');
}
