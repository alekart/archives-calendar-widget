/**
 * Pads `string` on the left side if it's shorter than `length`. Padding
 * characters are truncated if they exceed `length`.
 *
 * @param string {string|number}
 * @param length {number}
 * @param characters {string}
 * @returns {string}
 */
module.exports = function padStart(string, length, characters) {
  let newString = string.toString();
  if (string.length < length) {
    for (let i = string.length; i < length; i++) {
      newString = `${characters}${newString}`;
    }
  } else {
    return string;
  }
  return newString.slice(-length);
};
