
function isAuthenticated() {
  if (localStorage.getItem('fusio_consumer_access_token') && localStorage.getItem('fusio_consumer_expires_in')) {
    var expiresIn = new Date(localStorage.getItem('fusio_consumer_expires_in') * 1000);
    if (Date.now() < expiresIn) {
      return true;
    } else {
      localStorage.removeItem('fusio_consumer_access_token');
      localStorage.removeItem('fusio_consumer_expires_in');
    }
  }
  return false;
}

function login(username, password) {
  $.ajax({
    type: 'POST',
    url: fusio_url + 'consumer/token',
    headers: {
      'Authorization': 'Basic ' + btoa(username + ':' + password)
    },
    data: 'grant_type=client_credentials&scope=consumer',
    success: function(data){
      if (data.access_token && data.expires_in) {
        localStorage.setItem('fusio_consumer_access_token', data.access_token);
        localStorage.setItem('fusio_consumer_expires_in', data.expires_in);

        location.reload();
      } else {
        $('#loginError').html('The server respond with an successful status code but the response does not contain an access token').fadeIn();
      }
    },
    error: function(resp){
      var data = resp.responseJSON;
      if (data.error_description) {
        $('#loginError').html(data.error_description).fadeIn();
      }
    }
  });
}

function request(method, path, data, success, error) {
  $.ajax({
    type: method,
    url: fusio_url + path,
    headers: {
      'Authorization': 'Bearer ' + localStorage.getItem('fusio_consumer_access_token')
    },
    data: data,
    success: success,
    error: error
  });
}

function loadApps() {
  request('GET', 'consumer/app/grant', null, function(data){
    var html = '<ul>';
    for (var i = 0; i < data.entry.length; i++) {
      var grant = data.entry[i];
      var createDate = new Date(grant.createDate);
      html+= '<li>';
      html+= '<button class="btn btn-danger pull-right fusio-btn-grant-revoke" data-grant-id="' + grant.id + '">Revoke</button>';
      html+= grant.app.name + '<br>';
      html+= '<small>granted access on ' + createDate.toLocaleString() + '</small>';
      html+= '</li>'
    }
    html+= '</ul>';
    $('#authorizedApps').html(html);

    // add revoke button listener
    $('.fusio-btn-grant-revoke').click(function(){
      var grantId = $(this).data('grant-id');
      request('DELETE', 'consumer/app/grant/' + grantId, null, function(){
        $('button[data-grant-id="' + grantId + '"]').parent().fadeOut();
      });
    });
  });

  request('GET', 'consumer/app/developer', null, function(data){
    var html = '<ul>';
    for (var i = 0; i < data.entry.length; i++) {
      var app = data.entry[i];
      var createDate = new Date(app.date);
      html+= '<li>';
      html+= '<div class="btn-group pull-right" role="group">';
      html+= '<button class="btn btn-default fusio-btn-app-show" data-app-id="' + app.id + '">Show</button>';
      html+= '<button class="btn btn-danger fusio-btn-app-delete" data-app-id="' + app.id + '">Delete</button>';
      html+= '</div>';
      html+= app.name + '<br>';
      html+= '<small>created on ' + createDate.toLocaleString() + '</small>';
      html+= '</li>'
    }
    html+= '</ul>';
    $('#developerApps').html(html);

    // add info button listener
    $('.fusio-btn-app-show').click(function(){
      var appId = $(this).data('app-id');

      request('GET', 'consumer/app/developer/' + appId, null, function(data){
        var createDate = new Date(data.date);
        var scopesHtml = '';
        for (var i = 0; i < data.scopes.length; i++) {
          scopesHtml+= '<span class="label label-primary">' + data.scopes[i] + '</span>';
        }

        $('#appModalLabel').html(data.name);
        $('#appName').html(data.name);
        $('#appCreateDate').html(createDate.toLocaleString());
        $('#appScopes').html(scopesHtml);
        $('#appKey').val(data.appKey);
        $('#appSecret').val(data.appSecret);

        // @TODO show tokens and probably provide a way to remove a token
      });

      $('#appModal').modal({
        keyboard: false
      })
    });

    // add delete button listener
    $('.fusio-btn-app-delete').click(function(){
      var appId = $(this).data('app-id');
      request('DELETE', 'consumer/app/developer/' + appId, null, function(){
        $('button[data-app-id="' + appId + '"]').parent().parent().fadeOut();
      });
    });
  });
}

function loadAppInfo(responseType, clientId, redirectUri, scope, state) {
  request('GET', 'consumer/app/meta?client_id=' + encodeURIComponent(clientId) + '&scope=' + encodeURIComponent(scope), null, function(data){
    $('#appName').html(data.name);

    var scopesHtml = '<ul>';
    if (data.scopes) {
      for (var i = 0; i < data.scopes.length; i++) {
        scopesHtml+= '<li><b>' + data.scopes[i].description + '</b></li>';
      }
    }
    scopesHtml+= '</ul>';

    $('#appRequestedScopes').html(scopesHtml);
  });

}

function submitAccess(responseType, clientId, redirectUri, scope, state, allow) {
  var data = {
    responseType: responseType,
    clientId: clientId,
    redirectUri: redirectUri,
    scope: scope,
    state: state,
    allow: allow,
  };

  request('POST', 'consumer/authorize', data, function(data){
    if (data.redirectUri == '' || data.redirectUri == '#') {
      $('#appRequestPermission').css('display', 'none');
      $('#appResponseCode').css('display', 'block');
      $('#appCode').html(data.code);
    } else {
      location.href = data.redirectUri;
    }
  });
}

function getParameter(name) {
    var query = window.location.search.substring(1);
    var vars = query.split('&');
    for (var i = 0; i < vars.length; i++) {
        var pair = vars[i].split('=');
        if (decodeURIComponent(pair[0]) == name) {
            return decodeURIComponent(pair[1]);
        }
    }
    return null;
}

function guessFusioEndpointUrl() {
  var url = window.location.href;
  var removePart = function(url, sign) {
    var count = (url.match(/\//g) || []).length;
    var pos = url.lastIndexOf(sign);
    if (count > 2 && pos !== -1) {
      return url.substring(0, pos);
    }
    return url;
  }
  var parts = ['#', '?', '/', '/'];
  for (var i = 0; i < parts.length; i++) {
    url = removePart(url, parts[i]);
  }
  return url + '/index.php/';
}

function onLoad(){
  var responseType = getParameter('response_type');
  var clientId = getParameter('client_id');
  var redirectUri = getParameter('redirect_uri');
  var scope = getParameter('scope');
  var state = getParameter('state');

  // are we already authenticated
  if (!isAuthenticated()) {
    // show login form
    $('.fusio-login-container').fadeIn();
  } else {
    if (responseType == 'code' && clientId != '') {
      // authorization view
      $('.fusio-auth-container').fadeIn();

      // get app info
      loadAppInfo(responseType, clientId, redirectUri, scope, state);
    } else {
      // show apps
      $('.fusio-app-container').fadeIn();

      // request app list
      loadApps();
    }
  }

  $('#loginForm').submit(function(){
    login($('#username').val(), $('#password').val());
    return false;
  });

  $('#appGrantAccess').click(function(){
    submitAccess(responseType, clientId, redirectUri, scope, state, 1);
  });

  $('#appDenyAccess').click(function(){
    submitAccess(responseType, clientId, redirectUri, scope, state, 0);
  });
}

$(document).ready(onLoad);

if (typeof fusio_url === 'undefined') {
  fusio_url = guessFusioEndpointUrl();
}
