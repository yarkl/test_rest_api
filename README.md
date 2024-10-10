
<pre>
<h2>Project setup</h2>
1. cd docker
2. Install docker containers by running "make setup".
3. Ssh into php-cli container by running "make sh"
4. Apply migrations by running "doctrine:migrations:migrate"
</pre>
<pre>
Endpoints:
    signup:
        url: http://192.168.100.104/api/v1/signup (Ip address assigned in ./devops/docker-compose.yml)
        format: json
        body: {"login": "USER_NAME"}
        response: {"token": "TOKEN", "refresh_token": "REFRESH_TOKEN"}
        explanation: Use token field, from response, to send every further request, 
                     by adding "Authorization: Bearer TOKEN" header
    refresh-token
        url: http://192.168.100.104/api/v1/refresh-token
        format: json
        body: {"refresh_token": REFRESH_TOKEN}
        response: {"token": "TOKEN", "refresh_token": "REFRESH_TOKEN"}
        explanation: When your token has been expired send your REFRESH_TOKEN, from the signup response, 
                     to refresh your token
    create-document:
        url: http://192.168.100.104/api/v1/document
        format: json
        method: POST
        body: 
            {
                "payload": {
                    "document": true
                }
            }
        response: 
            {
                "document": 
                {
                    "id": UUID,
                    "status": "published",
                    "payload": {
                        "document": true
                    },
                    "createAt": DATE_TIME,
                    "modifyAt": DATE_TIME
                }
            }
    update-document:
        url: http://192.168.100.104/api/v1/document/UUID
        format: json
        method: POST
        body: 
            {
                "payload": {
                    "document": false
                }
            }
        response: 
            {
                "document": 
                {
                    "id": UUID,
                    "status": "draft",
                    "payload": {
                        "document": false
                    },
                    "createAt": DATE_TIME,
                    "modifyAt": DATE_TIME
                }
            }
    publish-document:
        url: http://192.168.100.104/api/v1/document/UUID/publish
        format: json
        method: POST
        body: 
            {
                "payload": {
                    "document": false
                }
            }
        response: 
            {
                "document": 
                {
                    "id": UUID,
                    "status": "published",
                    "payload": {
                        "document": false
                    },
                    "createAt": DATE_TIME,
                    "modifyAt": DATE_TIME
                }
            }        
</pre>