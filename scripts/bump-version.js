const fs = require('fs');
const yargs = require('yargs').argv;
const loadProjectJson = require('./utils/load-project-json');
const exec = require('./utils/exec');
const {bumpVersion} = require('./utils/bump-version');
const updatePluginVersion = require('./utils/bump-plugin-version');

/* ARGUMENTS FROM THE CLI */
/**
 * Indicate the bump operation to proceed on the version
 * Possible bumpOperations can be chained with comma separator (next,date):
 * - snap : will add '-snapshot' suffix
 * - next : will bump patch and add 'snapshot' suffix
 * - major : will bump major
 * - minor : will bump minor
 * - patch : will bump patch
 * - hash : will add the
 * - date : will add current date in MMDD format
 * - x.x.x or any other semver version
 *
 * @type {string}
 */
const bumpOperation = yargs._[0];
const nextIncArgument = yargs._[1];
/**
 * Indicates if the version is a release so the "Stable tag" in the readme will be set to this version
 * @type {boolean}
 */
const isRelease = yargs.release === 'true' || yargs.release === true;

/**
 * Provide `--nocommit` CLI argument to prevent to commit the
 * packages.json files on git after the bump.
 */
let shouldNotCommit = yargs.nocommit;

const packagePath = './package.json';
/**
 * @type {{name: string, version: string, engines: {node: string}, remotePath: {root: string, acc: string, release: string}}}
 */
const projectJson = loadProjectJson(packagePath);
const currentVersion = projectJson.version;
const bumpOperations = bumpOperation.split(',');
let newVersion;

if (bumpOperations && bumpOperations.length > 1) {
  newVersion = currentVersion;
  bumpOperations.forEach((operation) => {
    newVersion = bumpVersion(newVersion, operation);
  });
} else {
  newVersion = bumpVersion(currentVersion, bumpOperation, nextIncArgument);
}

if (currentVersion === newVersion) {
  console.log('Current version is the same as the new version. Nothing to do.');
  process.exit(0);
}

const packageData = setPackageVersion(loadProjectJson(packagePath), newVersion);
savePackage(packageData, packagePath);

updatePluginVersion(newVersion, isRelease);

if (bumpOperation !== 'hash' && !shouldNotCommit) {
  const filesToCommit = ['./package.json', './src/arcw.php', './src/readme.txt'];
  const commitMessage = bumpOperation === 'next'
    ? `Bump next version to ${newVersion}`
    : `Bump version to ${newVersion}`;
  exec(`git commit ${filesToCommit.join(' ')} -m "${commitMessage}"`);
}

// eslint-disable-next-line no-console
console.log(newVersion);

/**
 * Set Package version in provided package file data and return updated package content
 * @param packageData {{}}
 * @param version {string}
 * @returns {{}}
 */
function setPackageVersion(packageData, version) {
  return {...packageData, version};
}

/**
 * Write updated package file content
 * @param packageData {{}}
 * @param packagePath {string}
 */
function savePackage(packageData, packagePath) {
  const fileContent = `${JSON.stringify(packageData, null, 2)}\n`;
  fs.writeFileSync(packagePath, fileContent, 'utf-8');
}
