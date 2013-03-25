// Class from http://ejohn.org/blog/simple-javascript-inheritance/
// ---------------------
(function() {
  var initializing = false,
    fnTest = /xyz/.test(function() {
      xyz;
    }) ? /\b_super\b/ : /.*/;

  // The base Class implementation (does nothing)
  this.Class = function() {};

  // Create a new Class that inherits from this class
  Class.extend = function(prop) {
    var _super = this.prototype;

    // Instantiate a base class (but only create the instance,
    // don't run the init constructor)
    initializing = true;
    var prototype = new this();
    initializing = false;

    // Copy the properties over onto the new prototype
    for (var name in prop) {
      // Check if we're overwriting an existing function
      prototype[name] = typeof prop[name] == "function" && typeof _super[name] == "function" && fnTest.test(prop[name]) ? (function(name, fn) {
        return function() {
          var tmp = this._super;

          // Add a new ._super() method that is the same method
          // but on the super-class
          this._super = _super[name];

          // The method only need to be bound temporarily, so we
          // remove it when we're done executing
          var ret = fn.apply(this, arguments);
          this._super = tmp;

          return ret;
        };
      })(name, prop[name]) : prop[name];
    }

    // The dummy class constructor

    function Class() {
      // All construction is actually done in the init method
      if (!initializing && this.init) this.init.apply(this, arguments);
    }

    // Populate our constructed prototype object
    Class.prototype = prototype;

    // Enforce the constructor to be what we expect
    Class.prototype.constructor = Class;

    // And make this class extendable
    Class.extend = arguments.callee;

    return Class;
  };
})();

// Simple JavaScript Templating
// John Resig - http://ejohn.org/ - MIT Licensed
(function(){
  var cache = {};

  this.tmpl = function tmpl(str, data){
    // Figure out if we're getting a template, or if we need to
    // load the template - and be sure to cache the result.
    var fn = !/\W/.test(str) ?
      cache[str] = cache[str] ||
        tmpl(document.getElementById(str).innerHTML) :

      // Generate a reusable function that will serve as a template
      // generator (and which will be cached).
      new Function("obj",
        "var p=[],print=function(){p.push.apply(p,arguments);};" +

        // Introduce the data as local variables using with(){}
        "with(obj){p.push('" +

        // Convert the template into pure JavaScript
        str
          .replace(/[\r\t\n]/g, " ")
          .split("<%").join("\t")
          .replace(/((^|%>)[^\t]*)'/g, "$1\r")
          .replace(/\t=(.*?)%>/g, "',$1,'")
          .split("\t").join("');")
          .split("%>").join("p.push('")
          .split("\r").join("\\'")
      + "');}return p.join('');");

    // Provide some basic currying to the user
    return data ? fn( data ) : fn;
  };
})();

// typOf from http://gotochriswest.com/blog/2012/07/03/javascript-better-typeof-function/
// ---------------------
(function(_, g, u) {
  typeOf = function(o) {
    return o === g
      ? "global"
      : o == u
        ? o === u
          ? "undefined"
          : "null"
        : _.toString.call(o).slice(8, -1);
  };
})({}, this);

// Generate a unique integer id (unique within the entire client session).
(function(){
  var __idCounter = 0;
  this.uniqueId = function(prefix) {
    var id = ++__idCounter + '';
    return prefix ? prefix + id : id;
  };
})();

// Events from https://github.com/krasimir/EventBus/blob/master/src/EventBus.js
// ---------------------
(function(){
  var EventBusClass = {};
  EventBusClass = function() {
    this.listeners = {};
  };
  EventBusClass.prototype = {
    addEventListener:function(type, callback, scope) {
      var args = [];
      var numOfArgs = arguments.length;
      for(var i=0; i<numOfArgs; i++){
        args.push(arguments[i]);
      }
      args = args.length > 3 ? args.splice(3, args.length-1) : [];
      if(typeof this.listeners[type] != "undefined") {
        this.listeners[type].push({scope:scope, callback:callback, args:args});
      } else {
        this.listeners[type] = [{scope:scope, callback:callback, args:args}];
      }
    },
    removeEventListener:function(type, callback, scope) {
      if(typeof this.listeners[type] != "undefined") {
        var numOfCallbacks = this.listeners[type].length;
        var newArray = [];
        for(var i=0; i<numOfCallbacks; i++) {
          var listener = this.listeners[type][i];
          if(listener.scope == scope && listener.callback == callback) {

          } else {
            newArray.push(listener);
          }
        }
        this.listeners[type] = newArray;
      }
    },
    hasEventListener:function(type, callback, scope) {
      if(typeof this.listeners[type] != "undefined") {
        var numOfCallbacks = this.listeners[type].length;
        if(callback === undefined && scope === undefined){
          return numOfCallbacks > 0;
        }
        for(var i=0; i<numOfCallbacks; i++) {
          var listener = this.listeners[type][i];
          if(listener.scope == scope && listener.callback == callback) {
            return true;
          }
        }
      }
      return false;
    },
    dispatch:function(type, target) {
      var numOfListeners = 0;
      var event = {
        type:type,
        target:target
      };
      var args = [];
      var numOfArgs = arguments.length;
      for(var i=0; i<numOfArgs; i++){
        args.push(arguments[i]);
      };
      args = args.length > 2 ? args.splice(2, args.length-1) : [];
      args = [event].concat(args);
      if(typeof this.listeners[type] != "undefined") {
        var numOfCallbacks = this.listeners[type].length;
        for(var i=0; i<numOfCallbacks; i++) {
          var listener = this.listeners[type][i];
          if(listener && listener.callback) {
            var concatArgs = args.concat(listener.args);
            listener.callback.apply(listener.scope, concatArgs);
            numOfListeners += 1;
          }
        }
      }
    },
    getEvents:function() {
      var str = "";
      for(var type in this.listeners) {
        var numOfCallbacks = this.listeners[type].length;
        for(var i=0; i<numOfCallbacks; i++) {
          var listener = this.listeners[type][i];
          str += listener.scope && listener.scope.className ? listener.scope.className : "anonymous";
          str += " listen for '" + type + "'\n";
        }
      }
      return str;
    }
  };
  this.EventDispatcher = new EventBusClass();
})();

// Collection inspired in https://github.com/mayconbordin/jsCollections.git
// ---------------------
(function() {

  this.Collection = Class.extend({
    init: function(models) {
      this.cid = uniqueId('c');
      this.models = new Array();

      this.reset(models);
      this.initialize(models);
    },

    initialize: function(){},

    createModel: function(model){
      return model;
    },

    _onModelEvent: function(event, model, collection) {
      if ((event.type === 'add' || event.type === 'remove') && collection !== this) {
        return;
      }

      if (event.type === 'destroy') {
        this.removeObj(model);
      }

      if (model && event.type === 'change') {
        var index = this.indexOf(model);
        if (index > -1) {
          this.set(index, model);

          EventDispatcher.dispatch('change', this, 'change', model, this);
        }
      }
    },

    // When you have more items than you want to add or remove individually,
    // you can reset the entire set with a new list of models, without firing
    // any `add` or `remove` events. Fires `reset` when finished.
    reset: function(models) {
      if (models && typeOf(models) === 'Array') {
        if (models.length == 0) return;

        for (var i = 0, l = this.models.length; i < l; i++) {
          this.remove(i);
        }

        for (var i in models) {
          this.add(models[i]);
        }

        EventDispatcher.dispatch('reset', this, this);
      }

      return this;
    },

    add: function(model, index) {

      if (isNaN(index)) {
        index = this.models.length;
      }

      this._checkBoundInclusive(index);
      if (index < this.models.length) {
        for (var i = this.models.length; i > index; --i) {
          this.models[i] = this.models[i - 1];
        }
      }

      model = this.createModel(model);
      this.models[index] = model;

      if (model instanceof Model) {
        EventDispatcher.addEventListener('add', this._onModelEvent, this);
        EventDispatcher.addEventListener('change', this._onModelEvent, this);
        EventDispatcher.addEventListener('remove', this._onModelEvent, this);
        EventDispatcher.addEventListener('destroy', this._onModelEvent, this);
      }

      EventDispatcher.dispatch('add', this, model, this);

      return this;
    },

    indexOf: function(model) {
      for (var i = 0; i < this.models.length; ++i) {
        if (model instanceof Model && this.models[i] instanceof Model) {
          if (model.cid === this.models[i].cid) {
            return i;
          }
        } else {
          if (model === this.models[i]) {
            return i;
          }
        }
      }

      return -1;
    },

    lastIndexOf: function(model) {
      for (var i = this.models.length; i >= 0; --i) {
        if (model instanceof Model && this.models[i] instanceof Model) {
          if (model.cid === this.models[i].cid) {
            return i;
          }
        } else {
          if (model === this.models[i]) {
            return i;
          }
        }
      }

      return -1;
    },

    get: function(index) {
      this._checkBoundExclusive(index);

      return this.models[index];
    },

    getFirst: function() {
      return (this.isEmpty() ? null : this.get(0));
    },

    getLast: function() {
      return (this.isEmpty() ? null : this.get(this.models.lenght - 1));
    },

    set: function(index, model) {
      this._checkBoundExclusive(index);

      this.models[index] = model;

      return this;
    },

    sort: function(sortFunc) {
      if (sortFunc && sortFunc != null) {
        this.models.sort(sortFunc);
      } else {
        this.models.sort();
      }

      EventDispatcher.dispatch('sort', this, this);

      return this;
    },

    remove: function(index) {
      this._checkBoundExclusive(index);

      var model = this.models[index];
      for (var i = index; i < (this.models.length - 1); ++i) {
        this.models[i] = this.models[i + 1];
      }

      --this.models.length;

      EventDispatcher.dispatch('remove', this, model, this);

      return this;
    },

    removeObj: function(model) {
      var index = this.indexOf(model);

      if (index != -1) {
        this.remove(index);

        return true;
      } else {
        return false;
      }
    },

    clear: function() {
      this.models.length = 0;

      return this;
    },

    contains: function(model) {
      return (this.indexOf(model) != -1);
    },

    map: function(iterator) {
      var results = [];
      if (iterator == null && typeOf(iterator) !== 'Function') {
        return results;
      }

      for (var i = 0, l = this.models.length; i < l; i++) {
        results[results.length] = iterator.call(this, this.models[i], i);
      }

      return results;
    },

    isEmpty: function() {
      return this.models.length == 0;
    },

    toArray: function() {
      return this.models;
    },

    /*toJSON: function() {
      var result = [];
      for (var i = 0, l = this.models.length; i < l; i++) {
        var model = this.models[i];
        if (model instanceof Model) {
          console.log('aqui');
          result[result.length] = model.toJSON();
        } else {
          console.log('aca');
          result[result.length] = model;
        }
      }

      return result;
    },*/

    length: function() {
      return this.models.length;
    },

    // Iternal Implementations
    // -----------------------
    _checkBoundInclusive: function(index) {
      if (index < 0 || index > this.models.length) throw new Error("Index " + index + " is out of bounds [0," + (this.models.length - 1) + "]");
    },

    _checkBoundExclusive: function(index) {
      if (index < 0 || index >= this.models.length) throw new Error("Index " + index + " is out of bounds [0," + (this.models.length - 1) + "]");
    },

    toJSON: function() {
      return this.map(function(model){
        if (model instanceof Model) {
          return model.toJSON();
        }

        return model;
      });
    }
  });

})();

// Model
// ---------------------
(function() {
  this.Model = Class.extend({
    init: function(attrs) {
      this.cid = uniqueId('m');
      this.attributes = {};
      var settings = $.extend(true, this.defaults(), attrs);

      this.set(settings);
      this.initialize(attrs);
    },


    defaults: function() {
      return {};
    },

    initialize: function(options) {},

    get: function(key) {
      return this.attributes[key];
    },

    set: function(key, value) {
      if (key == null) return this;

      // Handle both `"key", value` and `{key: value}` -style arguments.
      if (typeOf(key) === 'Object') {
        for (attr in key) {
          this.attributes[attr] = key[attr];
        }
      } else {
        this.attributes[key] = val;
      }

      EventDispatcher.dispatch('change', this, this);

      return this;
    },

    toJSON: function() {
      return $.extend(true, {}, this.attributes);
    },

    destroy: function() {
      EventDispatcher.dispatch('destroy', this, this);

      return this;
    },

    listenTo: function(target, event, callback) {
      EventDispatcher.addEventListener(event, $.proxy( function(event) {
        if (event.target == target) {
          var args = [];
          for(var i = 1, len = arguments.length; i < len; i+=1){
            args.push(arguments[i]);
          }
          callback.apply(this, args);
        }
      }, this ));
    }

  });
})();

// View
// ---------------------
(function() {
  this.View = Class.extend({
    init: function(options) {
      var settings = $.extend(true, this.defaults(), options);
      this.validOptions = new Collection(['model', 'attributes', 'className', 'tagName', 'events']);

      this._configure(settings);
      this._ensureElement();

      this.events = this.events || {};
      this._delegateEvents(this.events);

      this.initialize(options);
    },

    defaults: function() {
      return {
        tagName: 'div',
        el: null
      };
    },

    initialize: function(options) {},

    render: function() {
      return this;
    },

    remove: function() {
      this.$el.remove();

      return this;
    },

    _configure: function(options) {
      for (var i in options) {
        if (this.validOptions.contains(i)) {
          this[i] = options[i];
        }
      }
    },

    _ensureElement: function() {
      if (!this.el) {
        this.$el = $('<' + this.tagName + '>').attr(this.attributes || {});
      } else {
        this.$el = $(this.el);
      }

      this.el = this.$el.get(0);

      return this;
    },

    $: function(selector) {
      return this.$el.find(selector);
    },

    listenTo: function(target, event, callback) {
      EventDispatcher.removeEventListener(event, $.proxy( function(event) {
        if (event.target == target) {
          var args = [];
          for(var i = 1, len = arguments.length; i < len; i+=1){
            args.push(arguments[i]);
          }
          callback.apply(this, args);
        }
      }, this ));

      EventDispatcher.addEventListener(event, $.proxy( function(event) {
        if (event.target == target) {
          var args = [];
          for(var i = 1, len = arguments.length; i < len; i+=1){
            args.push(arguments[i]);
          }
          callback.apply(this, args);
        }
      }, this ));
    },

    _delegateEvents: function(events) {
      // Cached regex to split keys for `delegate`.
      var delegateEventSplitter = /^(\S+)\s*(.*)$/;

      this._undelegateEvents();
      for (var key in events) {
        var method = events[key];
        if (!typeOf(method) !== 'Function') {
          method = this[events[key]];
        }

        if (!method) {
          throw new Error('Method "' + events[key] + '" does not exist');
        }

        var match = key.match(delegateEventSplitter);
        var eventName = match[1], selector = match[2];

        method = $.proxy( method, this );
        //eventName += '.delegateEvents' + this.cid;
        if (selector === '') {
          this.$el.on(eventName, method);
        } else {
          this.$el.delegate(selector, eventName, method);
        }
      }
    },

    // Clears all callbacks previously bound to the view with `delegateEvents`.
    // You usually don't need to use this, but may wish to if you have multiple
    // Backbone views attached to the same DOM element.
    _undelegateEvents: function() {
      this.$el.undelegate();
    }

  });
})();