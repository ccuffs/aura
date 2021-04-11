var ccuffs = ccuffs || {};

ccuffs.AuraInternal = function(external, settings) {
    this.API_URL = 'http://aura.dev.local.com/api';

    this.defaultSettings = {
        token: '',
        inputId: undefined,
    }

    this.settings = {};
    this.external = external;
    this.request = new ccuffs.AuraRequest();
    this.inputElement = undefined;

    this.init(settings);
}

ccuffs.AuraInternal.prototype.init = function(settings) {
    this.settings = this.createSettings(settings);
    console.debug(this.settings);

    if(this.settings.inputId) {
        this.initInputForInteraction(this.settings.inputId);
    }
}

/**
 * 
 * @param {*} inputId 
 */
ccuffs.AuraInternal.prototype.initInputForInteraction = function(inputId) {
    const input = document.getElementById(inputId);
    const self = this;

    if(!input) {
        throw 'Unable init input with id "' + inputId + '" for interaction.';
    }

    input.addEventListener('keydown', function(event) {
        self.handleInputInteractionKeyDown(event);
    });

    this.inputElement = input;
}

ccuffs.AuraInternal.prototype.getInputValue = function() {
    return this.inputElement ? this.inputElement.value : '';
}

ccuffs.AuraInternal.prototype.handleInputInteractionKeyDown = function(event) {
    if(event.key == 'Enter') {
        const value = this.getInputValue();
        this.request.post(this.API_URL + '/interact', {q: value});
    }
}

ccuffs.AuraInternal.prototype.createSettings = function(settings) {
    var informedSettings = settings || {};
    var finalSettings = {};

    for(var name in this.defaultSettings) {
        finalSettings[name] = this.defaultSettings[name];

        if(informedSettings[name] !== undefined) {
            finalSettings[name] = informedSettings[name];
        }
    }

    return finalSettings;
}
