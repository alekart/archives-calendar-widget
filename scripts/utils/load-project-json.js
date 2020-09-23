const path = require('path');
const fs = require('fs');

/**
 * Load package.json file from the project where this package is installed
 * @returns {object}
 */
function loadProjectJson(relativePath) {
  const resolvedPath = path.resolve(relativePath);
  /**
   * @type {object}
   */
  let json;
  try {
    json = JSON.parse(fs.readFileSync(resolvedPath, 'utf8'));
  } catch (e) {
    const message = `${resolvedPath} could not be loaded`;
    console.error(message);
    throw new Error();
  }
  return json;
}

module.exports = loadProjectJson;
