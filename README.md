# short-url
## Simple URL Shortener

This application can be used to create shortened versions of URL's.
Simply enter an URL you want to share into the text field and press the button.
Also you can choose desired short name, but it should be unique.

## Installation
1. Clone or download repository.
2. Upload to your host
3. Point domain root dir to the `www` folder
4. Create new MySQL database and upload `db/full.sql`
5. Edit `config/default.ini` - 
  + Enter your DB credentials
  + Choose verbosity level (0 - disable log; 2 - all messages)
6. Set write permission on `log` folder for web-server user

## API
There is only one method implemented.

### create

Example: http://www.domain.com/api?method=create&url=...&short=...

Just send GET request to the `/api` with following parameters:
* method = create
* url = \<your URL\>
* short = \<short name\> (optional)

The response will be in JSON format.
* `result` - "error" or "ok"
* `errors` - array of errors (if `result` == "error")
* `url` - resulting URL (if `result` == "ok")
