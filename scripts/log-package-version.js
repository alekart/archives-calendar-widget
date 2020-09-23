const loadProjectJson = require('./utils/load-project-json');
const pckg = loadProjectJson('./package.json');

console.log(pckg.version);
