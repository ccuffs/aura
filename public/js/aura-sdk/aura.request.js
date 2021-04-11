var ccuffs = ccuffs || {};

ccuffs.AuraRequest = function() {
    this.defaultHeaders = {};
}

ccuffs.AuraRequest.prototype.addHeaders = function(request, headers) {
    if(!headers) {
        return;
    }

    for(var name in headers) {
        request.setRequestHeader(name, headers[name]);
    }
}

ccuffs.AuraRequest.prototype.onError = function(event) {
    console.error('Connection problem: ', event);
}

ccuffs.AuraRequest.prototype.onServerError = function(response) {
    console.error('Server error:', response);
}

ccuffs.AuraRequest.prototype.get = function(url, callback, headers) {
    this.doRequest('GET', url, undefined, callback, headers);
}

ccuffs.AuraRequest.prototype.post = function(url, data, callback, headers) {
    this.doRequest('POST', url, data, callback, headers);
}

ccuffs.AuraRequest.prototype.createPayload = function(data) {
    if(data == undefined) {
        return undefined;
    }

    var payload = new FormData();

    for(var name in data) {
        payload.set(name, data[name]);
    }

    return payload;
}

ccuffs.AuraRequest.prototype.doRequest = function(verb, url, data, callback, headers) {
    const self = this;
    var request = new XMLHttpRequest();
    var payload = this.createPayload(data);

    console.debug(verb, url, payload);

    request.open(verb, url, true);

    this.addHeaders(request, this.defaultHeaders);
    this.addHeaders(request, headers);

    request.onload = function() {
        if(this.status >= 200 && this.status < 400) {
            var data = JSON.parse(this.response);
            if(callback) {
                callback(data);
            }
        } else {
            self.onServerError(this.response);
        }
    };

    request.onerror = this.onError;
    request.send(payload);
}