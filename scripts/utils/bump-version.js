const semver = require('semver');
const getGitRef = require('../utils/get-git-ref.function');
const padStart = require('./padStart');

const hashRegEx = /((?!-snapshot))(-.{8})$/;

/**
 * Update current version with with a bump or an add-on as snapshot, date or hash
 *
 * Possible bumpOperation:
 * - snap : will add '-snapshot' suffix
 * - next : will bump patch and add 'snapshot' suffix
 * - major : will bump major
 * - minor : will bump minor
 * - patch : will bump patch
 * - hash : will add the
 * - date : will add current date in MMDD format
 * - x.x.x or any other semver version
 *
 * @param currentVersion {string}
 * @param bumpOperation {string} version string or a bump argument
 * @param [nextIncrementParam] {'major' | 'minor' | 'remove' | 'force'} additional bump argument for next version bumping
 * @returns {string} updated version string
 */
function bumpVersion(currentVersion, bumpOperation, nextIncrementParam) {
  if (!bumpOperation) {
    console.error('No version provided');
    process.exit(1);
  }
  /**
   * The version that will be written to the package
   * @type string
   */
  let updatedVersion;

  switch (bumpOperation) {
    case 'snap':
      updatedVersion = markSnapshot(currentVersion);
      break;
    case 'next':
      let increment = 'patch';
      if (nextIncrementParam && ['major', 'minor'].indexOf(nextIncrementParam) > -1) {
        increment = nextIncrementParam;
      }
      updatedVersion = semver.inc(cleanVersion(currentVersion), increment);
      updatedVersion = markSnapshot(updatedVersion);
      break;
    case 'hash':
      // only used in automatic acceptance build
      updatedVersion = removeHash(currentVersion);
      if (nextIncrementParam === 'remove') {
        break;
      }
      updatedVersion = markHash(updatedVersion);
      break;
    case 'date':
      // only used in automatic acceptance build
      updatedVersion = markDate(currentVersion);
      break;
    case 'major':
    case 'minor':
    case 'patch':
      updatedVersion = semver.inc(cleanVersion(currentVersion), bumpOperation);
      break;
    case 'release':
    case 'clean':
      // removes "snapshot" from the version
      updatedVersion = cleanVersion(currentVersion);
      break;
    default:
      updatedVersion = bumpOperation;
  }

  if (!semver.valid(updatedVersion)) {
    console.error(`Invalid version format ${updatedVersion}`);
    process.exit(1);
  }

  if (bumpOperation !== 'hash'
    && bumpOperation !== 'snap'
    && bumpOperation !== 'date'
    && (nextIncrementParam !== 'force' && semver.lt(updatedVersion, currentVersion))
  ) {
    console.error('New version should be greater than the current');
    process.exit(1);
  }

  return updatedVersion;
}

/**
 * Remove any extras from the version and keep only MAJOR.MINOR.PATCH format
 * @param version {string}
 * @returns {string | null}
 */
function cleanVersion(version) {
  return semver.valid(semver.coerce(version));
}

/**
 * Add "-snapshot" suffix to the version
 * @param version {string}
 * @returns {string}
 */
function markSnapshot(version) {
  return `${cleanVersion(version)}-snapshot`;
}

/**
 * Add "-MMDD" (moth, day) date suffix to the version
 * @param version {string}
 * @returns {string}
 */
function markDate(version) {
  const date = new Date();
  const m = date.getMonth() + 1;
  const d = date.getDate();
  return `${version}-${padStart(m, 2, '0')}${padStart(d, 2, '0')}`;
}

/**
 * Add commit hash suffix to the version
 * @param version {string}
 * @returns {string}
 */
function markHash(version) {
  return `${version}-${getGitRef()}`;
}

/**
 * Remove the commit hash from the end of the provided version if present
 * @param version {string}
 * @returns {string}
 */
function removeHash(version) {
  return version.replace(hashRegEx, '');
}

module.exports = {
  bumpVersion,
};
