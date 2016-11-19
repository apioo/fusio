
## Config

The config contains system wide settings. In the following we explain some 
important settings which you most likely need to configure.

* `mail_register_body`  
  If a new user registers through the consumer app he receives an activation 
  mail. Through this setting you can configure the text and adjust the 
  activtaion url
* `recaptcha_secret`  
  If provided the consumer registration can show an google recaptcha which 
  prevents automatic registration. You also have to provide the recaptcha public
  key to the consumer app
* `scopes_default`  
  Those are the scopes which are assigned by default if a new user registers
* `provider_facebook_secret` `provider_github_secret` `provider_google_secret`  
  If provided a user can login through those remote providers. You also have
  to provide the app key to the consumer app 
