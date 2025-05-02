
Laravel 11
Middleware - Custom Encryption data for specific client


We will implemented 
encrypt and decrypt on sensitive data with laravel custom encryption class


Example :
---------
value of key = valuecustom12345

encrypted key = eyJpdiI6IjB0dHRMeEVFT3RDN3FNNkhLK1VPYXc9PSIsInZhbHVlIjoiRTFpZGFhVXRWOGNNeEJGZmFhdGlVVkh0czNMbFd1Z0hJaGxWbTF0VHZZU1dkdmhzQXRGVXhxU3BCZVJFVnVGRSIsIm1hYyI6IjI5MTNlNGI1ZDNkNDU1YTMyMDlmM2NkNjNjYzE4MmY0YjBkMWUxNmQ2NDI3MTM5NTNmNTcyNjJiYzlmYTgzZTUiLCJ0YWciOiIifQ==

=====================================
RestAPI example:

Output Encrypted Response for client:
-------------------------------------
{
  data: {
    contents: 'ZXlKcGRpSTZJbTF4YVhZdldIUkdlR0ZCVEZKMUszTlFSMjlOUjFFOVBTSXNJblpoYkhWbElqb2liVEE0WjBoWU1UTmphV2xyVGtsaU5rcDViSFIxYm5STldrOW1WM1pITkVoUVJrZFpTRUZRV2t4VVdqTm1TMGMwU0RaQlNISXZNM0ZtV1dGM2IwUkJVM1I0TmlzM2JtRlJZbEoxTTNwSU5qSllORTVQV1V4SFUyd3JOVTlwVjJFeVFtdE5SM2hNV1dKc1VDdGtjall4V0VwWWNIZFRaalJ3Ym5KMk9XZDNiVUo2Y0N0R2VuVmlibTlTUlZZcmJsSk1jbXhOTlVsc01VOTRNM0Z2TW1GME4xbzFTVVpzTjJ4VmFXOWFPVVJoVkV4R1JHOXhNRTVVWTJWdk1ETXJZVlpDYVdsVU5tY3hWWEF2UVRaRlJXbEdiV013TDNvMFFUMDlJaXdpYldGaklqb2lOMlJqTVRNME1tVmpOakl3TW1JMU9XWXhPVGMwT0dVM01HWTFOR1JpWWpabE5URXlNR0prTWpReVlXSTVZelkwWmprNVlUVTVOVGxtTW1RMVltTTJaaUlzSW5SaFp5STZJaUo5'
  },
  meta: {
    app: 'Laravel',
    version: '1.0.0',
    key: 'eyJpdiI6InEva0lsY052VGhKWlpXRkRlWFNMc1E9PSIsInZhbHVlIjoiTnBmM2QzejVzMmdrcGdibXNOOTFBYUVUU3lGTjRlc2RMRmJhSmx4RVBQTUhIcHZ6TVpDaytJQmVLS0VvUm5TVSIsIm1hYyI6IjhkN2FkNGJkMDU4Zjc1ZjcxNGRkNjY3OTE4OTRmZGVlZjVhZDgzZTZmNjVjZWI2YmRiMzA5NjM1YzVhZDVmNDkiLCJ0YWciOiIifQ=='
  }
}

=====================================
Flow
On client side to decrypt data:

1) Decrypt meta key:
key will used to decrypt the contents
-------------------------------------
Get Encrypted key:
key: 'eyJpdiI6InEva0lsY052VGhKWlpXRkRlWFNMc1E9PSIsInZhbHVlIjoiTnBmM2QzejVzMmdrcGdibXNOOTFBYUVUU3lGTjRlc2RMRmJhSmx4RVBQTUhIcHZ6TVpDaytJQmVLS0VvUm5TVSIsIm1hYyI6IjhkN2FkNGJkMDU4Zjc1ZjcxNGRkNjY3OTE4OTRmZGVlZjVhZDgzZTZmNjVjZWI2YmRiMzA5NjM1YzVhZDVmNDkiLCJ0YWciOiIifQ=='

Decrypted key:
fullMetaKey  :  valuecustom12345Bb7sBr8vxntasNyJ
validKey     :  valuecustom12345 // 16-alphanum string


2) Decrypt the contents:
Contents can be opened using a valid key
otherwise the client 
will fail to obtain valid data.
----------------------------------------
// Decrypt contents
contents := laravel.DecryptString(validKey, contents)

Valid output of contents:
[
  {
    id: '01jp2n06265pcyfr5pxyahdgkc',
    name: 'Album 1',
    description: 'Rem possimus consequatur ut fuga. Mollitia impedit ad vel esse eius sint.'
  }
]

========================

Let's test this feature, 
Besides using postman for testing,

we will also use client program simulation 
with Golang and NodeJS 
to make this feature more realistic to implement.




==========================

This module is made very flexible so that its settings can be adjusted.


Let's take a look at the configuration file.
and testing with postman


===========================

Using postman alone for testing is not enough 

let's test this feature using a real client.

let's do some testing using Golang and NodeJS

