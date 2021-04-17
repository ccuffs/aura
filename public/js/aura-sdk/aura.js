var ccuffs = ccuffs || {};

ccuffs.Aura = function(settings) {
    this.internal = new ccuffs.AuraInternal(this, settings);
}

ccuffs.Aura.prototype.interact = function(text) {
    if(!text) {
        throw 'Empty or undefined interaction.';
    }

    this.internal.postInteraction(text);
}
