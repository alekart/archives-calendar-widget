export function removeFromArray<T>(array: T[], value: T): T[] {
  const arrayPos = array.indexOf(value);
  if (arrayPos === -1) {
    return [...array];
  }
  const updated = [...array];
  updated.splice(arrayPos, 1);
  return updated;
}

export function addUniqueToArray<T>(array: T[], value: T): T[] {
  const inArray = array.indexOf(value) !== -1;
  if (inArray) {
    return [...array];
  }
  return [...array, value];
}
