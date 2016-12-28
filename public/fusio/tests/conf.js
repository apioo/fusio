'use strict';

exports.config = {
  seleniumAddress: 'http://localhost:4444/wd/hub',
  specs: [
    'app/account.js',
    'app/action.js',
    'app/app.js',
    'app/config.js',
    'app/connection.js',
    'app/log.js',
    'app/rate.js',
    'app/routes.js',
    'app/schema.js',
    'app/scope.js',
    'app/statistic.js',
    'app/user.js',
    'app/import.js'
  ],
  baseUrl: 'http://127.0.0.1:8008/fusio/index.htm',
  capabilities: {
    browserName: 'firefox'
  },
  onPrepare: function() {
    // login
    browser.driver.get('http://127.0.0.1:8008/fusio/index.htm#/login');

    element(by.model('credentials.username')).sendKeys('Developer');
    element(by.model('credentials.password')).sendKeys('qf2vX10Ec3wFZHx0K1eL');

    var EC = protractor.ExpectedConditions;
    var submitButton = element(by.css('button[type=submit]'));

    return browser.wait(EC.elementToBeClickable(submitButton), 5000).then(function(){

      submitButton.click();

      element(by.css('body')).getAttribute('innerHTML').then(function(value){
        console.log(value);
      });

      return browser.driver.wait(function() {
        return browser.driver.getCurrentUrl().then(function(url) {
          console.log(url);
          return /#\/dashboard/.test(url);
        });
      }, 10000);

      
    });
  }
};
