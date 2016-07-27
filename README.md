# Messenger-PHP

This is a set of classes to facilitate the creation of Facebook Messenger bots in PHP.

The main BotApp class implements:
* Webhook validation routine.
* Debugging info saved to a log file (configurable, uses bot.log as default).
* JSON message parsing and dispatching to methods implemented in child class.
* Session handling via custom session ids. This uses the standard PHP session functions.
* Methods for sending text, quick reply, template and attachments.
* Convenience methods for sending image, video, audio, file (calls attachment method internally).

There are also some PHP classes for building the messages to be sent. Instead of crafting JSON strings, you can just create the objects.

This is still a work in progress, but is already functional.

To get started, you need to create a Page in Facebook, as well as an App. Follow these steps:
1. Create a new Page in Facebook (or you can use one you already have)
2. Add a new App in https://developers.facebook.com/
3. In "Products", you need to add "Messenger"
4. In Messenger, you will generate a Page Access Token. Select the page, and copy the token that was created.
5. Setup webhooks: you need to enter the callback URL (it's the full URL to your PHP script) as well as a verify token (this is a random string). From the checkboxes below, make sure at least **messages** and **messages_postbacks** are checked.
6. In your PHP script, put both Page Access and Verify tokens in the $config array used when initializing the class.
7. Verify and Save, and if all is well, you should get a green tick. If not, check your web server log, or the generated debug log.
8. Subscribe your webhook to your Page events by selecting it below and clicking **Subscribe**

Some much needed stuff that needs to be done:
* Better class documentation, with examples.
* Better validation and error handling.
* Airline and receipt templates are not implemented yet.
