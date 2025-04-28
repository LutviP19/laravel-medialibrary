// load variables
const laravel = require("./laravel");
var Client = require('node-rest-client').Client;

// Header X-Value
const encrypt = laravel.encrypt('custom');
// console.log(encrypt);

// direct way
var client = new Client();
var endpoint = "http://127.0.0.1:8000/api/album";
var args = {
	headers: { "Accept":"application/json", 
                "Content-Type": "application/json", 
                "Authorization": "Bearer 24THBCnd33NFyG4pCabDcVmfWpLpgJoAN2m6zVjY39539c46", 
                "X-Value": encrypt 
             } // request headers
};

client.get(endpoint, args,
	function (data, response) {
		console.log("Calling API...");
		// raw response
		// console.log(response);

		// parsed response body as js object
		// console.log('response: ', data);
		// console.log('==============================')

		// Parse Json
		let metaData = data.meta.key;
		console.log('metaData: ', metaData)

		console.log('==============================')
		let fullMetaKey = laravel.decrypt(metaData);
		console.log('fullMetaKey : ', fullMetaKey)
		// console.log('Len of fullMetaKey: ', fullMetaKey.length)

		let metaKey = fullMetaKey.substr(0, 16);		
		console.log('metaKey     : ', metaKey)
		console.log('Len of metaKey  : ', metaKey.length)
		console.log('Type of metaKey : ', typeof metaKey)

		// The base64 decoded string
		let base64string = data.data.contents;
		let bufferObj = Buffer.from(base64string, "base64");
		let decodedString = bufferObj.toString("ascii");
		// console.log(decodedString)
		console.log('==============================')

		// content JSON
		let contents = laravel.decrypt(decodedString, metaKey)
		let contentJson = JSON.parse(contents);

		console.log('contents: ', contentJson);
		console.log('Total-Data: ', contentJson.length);
	});