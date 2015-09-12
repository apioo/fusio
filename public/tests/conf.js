exports.config = {
  seleniumAddress: 'http://localhost:4444/wd/hub',
  specs: [
    'app/action.js',
    'app/app.js',
    'app/connection.js',
    'app/log.js',
    'app/routes.js',
    'app/schema.js',
    'app/scope.js',
    'app/statistic.js',
    'app/user.js'
  ],
  baseUrl: 'http://127.0.0.1:8008/backend.htm',
  capabilities: {
    browserName: 'firefox'
  },
  onPrepare: function() {
    // login
    browser.driver.get('http://127.0.0.1:8008/backend.htm');
    browser.driver.findElement(by.id('username')).sendKeys('Developer');
    browser.driver.findElement(by.id('password')).sendKeys('3632465b3b5d1b8b4b8e56f74600da00cea92baf');
    browser.driver.findElement(by.css('button[type="submit"]')).click();

    return browser.driver.wait(function() {
      return browser.driver.getCurrentUrl().then(function(url) {
        return /#\/dashboard/.test(url);
      });
    }, 10000);
  }
};
