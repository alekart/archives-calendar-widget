const {execSync} = require('child_process');

/**
 * Preconfigured execSync
 * @param command {string}
 */
function exec(command) {
  const result = execSync(command);
  return result.toString('utf8');
}

module.exports = exec;
