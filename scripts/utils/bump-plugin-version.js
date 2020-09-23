const fs = require('fs');
const path = require('path');

/**
 *
 * @param version {string}
 * @param release {boolean}
 */
function updatePluginVersion(version, release = false) {
  const updatedPhp = loadFile('./src/arcw.php').replace(/(	\* Version: ).+/, `$1${version}`);
  saveFile('./src/arcw.php', updatedPhp);
  if (release) {
    const txtFileContent = loadFile('./src/readme.txt').replace(/(Stable tag: ).+/, `$1${version}`);
    saveFile('./src/readme.txt', txtFileContent);
  }
}

/**
 * Load package.json file from the project where this package is installed
 * @returns {object}
 */
function loadFile(relativePath) {
  const resolvedPath = path.resolve(relativePath);
  /**
   * @type {object}
   */
  let file;
  try {
    file = fs.readFileSync(resolvedPath, 'utf8');
  } catch (e) {
    const message = `${resolvedPath} could not be loaded`;
    console.error(message);
    throw new Error();
  }
  return file;
}

function saveFile(path, content) {
  fs.writeFileSync(path, content, 'utf-8');
}

module.exports = updatePluginVersion;
