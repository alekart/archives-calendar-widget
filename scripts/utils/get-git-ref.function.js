const exec = require('../utils/exec');

/**
 * As the git.short return a hash 7 chars long but we want 8
 * we add an alternative to substr the long version
 * @param long {boolean}
 * @returns {string}
 */
module.exports = function getGitRef(long = false) {
  const hash = exec('git rev-parse HEAD');
  return long ? hash : hash.substr(0, 8);
};
