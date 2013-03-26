/*
 * This file is part of the Rule application.
 *
 * (c) Ivannis Suárez Jérez
 *
 */


// Load the application once the DOM is ready, using `jQuery.ready`:
$(function() {
    (function() {

        // Factor Model
        // ----------
        // Our basic **Factor** model.

        var Factor = Model.extend({

            defaults: function() {
                return {
                    leftOperand: null,
                    operator: 'equals',
                    rightOperand: null
                };
            }
        });

        // Term Model
        // ----------

        // Our basic **Term** model.
        var Term = Model.extend({

            // Default attributes for the term.
            defaults: function() {
                return {
                    operator: null,
                    factor: null,
                    actions: {
                        destroy: true
                    }
                };
            },

            // Ensure that each term created has `factor`.
            initialize: function(options) {
                var factor = this.get('factor') || { operator: 'equals'};

                if (this.get('operator') == null) {
                    this.set({
                        'actions': { destroy: false }
                    });
                }

                this.set({
                    "factor": new Factor(factor)
                })

                this.listenTo(this.get('factor'), 'change', $.proxy(function() {
                    EventDispatcher.dispatch('change', this, this);
                }, this));
            },

            toJSON: function() {
                var json = this._super();

                json['factor'] = json['factor'].toJSON();

                return json;
            }

        });

        // CompountTerm Model
        // ----------

        // Our basic **CompountTerm** model.
        var CompountTerm = Model.extend({

            // Default attributes for the term.
            defaults: function() {
                return {
                    operator: null,
                    terms: null
                };
            },

            // Ensure that each term created has `terms`.
            initialize: function() {
                var terms = this.get('terms') || [{
                    type: 'simple-term',
                    actions: {
                        destroy: false
                    }
                }, {
                    type: 'simple-term',
                    operator: 'logicalAnd',
                    actions: {
                        destroy: false
                    }
                }];

                this.set({
                    "terms": new TermList(terms)
                });
            },

            toJSON: function() {
                var json = this._super();

                json['terms'] = json['terms'].toJSON();

                return json;
            }

        });

        // The collection of terms is backed by *localStorage* instead of a remote
        // server.
        var TermList = Collection.extend({

            createModel: function(model) {
                if (model['type'] == 'simple-term') {
                    return new Term(model);
                }

                return new CompountTerm(model);
            }

        });

        // The DOM element for a term item...
        var TermView = View.extend({

            //... is a div tag.
            tagName:  "div",

            // div attributes
            attributes: {
                "class": "rule-term"
            },

            // Cache the template function for a single item.
            template: tmpl($('#rule-term-template').html()),

            // The DOM events specific to an item.
            events: {
                "click .rule-logical .btn"           : "toggleOperator",
                "change .rule-left-variable select"  : "updateLeftOperand",
                "change .rule-operator select"       : "updateOperator",
                "change .rule-right-variable select" : "updateRightOperand",
                "hover"                              : "toggleDestroy",
                "click a.destroy"                    : "clear"
            },

            initialize: function(options) {
                this.index = options.index || '';

                this.listenTo(this.model, 'change', this.reload);
                this.listenTo(this.model, 'destroy', this.remove);
            },

            // Re-render the titles of the rule item.
            render: function() {
                this.$el.html(this.template( $.extend(true, { index: this.index }, this.model.toJSON()) ));
                this.destroyer = this.$('a.destroy');

                return this;
            },

            reload: function() {
                this.render();
                this.onAppend();
            },

            onAppend: function() {
                // create a chosen widgets
                this.$el.find('select[data-type="chosen"]').each(function () {
                  $(this).chosen({
                        no_results_text: $(this).attr('no-result-text'),
                        max_selected_options: $(this).attr('max-selected'),
                        allow_single_deselect: $(this).attr('single-deselect')
                    });
                });
            },

            updateLeftOperand: function(e) {
                e.preventDefault();

                this.model.get('factor').set({
                    'leftOperand': $(e.target).val()
                });
            },

            updateOperator: function(e) {
                e.preventDefault();

                this.model.get('factor').set({
                    'operator': $(e.target).val()
                });
            },

            updateRightOperand: function(e) {
                e.preventDefault();

                this.model.get('factor').set({
                    'rightOperand': $(e.target).val()
                });
            },

            // toggle logical operator button
            toggleOperator: function(e){
                e.preventDefault();

                this.model.set({
                    'operator': $(e.target).attr('data-value')
                });
            },

            toggleDestroy: function(event) {
                if (event.type == 'mouseenter') {
                    this.destroyer.show();

                    return true;
                }

                this.destroyer.hide();
            },

            // Remove the item, destroy the model.
            clear: function() {
                this.model.destroy();
            }

        });


        // The DOM element for a term item...
        var CompountTermView = View.extend({

            //... is a div tag.
            tagName:  "div",

            // div attributes
            attributes: {
                "class": "rule-compount-term"
            },

            // Cache the template function for a single item.
            template: tmpl($('#rule-compount-term-template').html()),

            // The DOM events specific to an item.
            events: {
                "click .rule-compount-term-logical .btn" : "toggleOperator",
                "click .append-rule a"                   : "createTerm",
                "hover"                                  : "toggleDestroy",
                "click a.destroy"                        : "clear"
            },

            initialize: function(options) {
                this.index = options.index || '';
                this.terms = this.model.get('terms');

                this.listenTo(this.model, 'change', this.reload);
                this.listenTo(this.model, 'destroy', this.remove);

                this.listenTo(this.terms, 'add', this.addOne);
                this.listenTo(this.terms, 'reset', this.addAll);

            },

            // Re-render the titles of the rule item.
            render: function() {
                this.$el.html(this.template( $.extend(true, { index: this.index }, this.model.toJSON()) ));

                this.container = this.$('.rule-aggregator');
                this.destroyer = this.$('a.destroy');

                var self = this;
                $.each(this.terms.toArray(), function(index){
                    var view = new TermView({ model: this, index: self.index + '[terms]' + '[' + index + ']' });
                    $(view.render().$el).insertBefore(self.container);
                });

                return this;
            },

            reload: function() {
                this.render();
                this.onAppend();
            },

            onAppend: function() {
                // create a chosen widgets
                this.$el.find('select[data-type="chosen"]').each(function () {
                    $(this).chosen({
                        no_results_text: $(this).attr('no-result-text'),
                        max_selected_options: $(this).attr('max-selected'),
                        allow_single_deselect: $(this).attr('single-deselect')
                    });
                });
            },

            createTerm: function(e) {
                e.preventDefault();

                this.terms.add({
                    type:     $(e.target).attr("data-type"),
                    operator: $(e.target).attr("data-value")
                });
            },

            addOne: function(term) {
                var view = new TermView({ model: term, index: this.index + '[terms]' + '[' + (this.terms.length()-1) + ']' });

                $(view.render().el).insertBefore(this.container);
                this.onAppend();
            },

            // Add all items in the **Terms** collection at once.
            addAll: function() {
                $.each($.proxy( function(index, term){
                    this.addOne(term);
                }, this))
            },

            // toggle logical operator button
            toggleOperator: function(e){
                e.preventDefault();

                this.model.set({
                    'operator': $(e.target).attr('data-value')
                });
            },

            toggleDestroy: function(event) {
                if (event.type == 'mouseenter') {
                    this.destroyer.show();

                    return true;
                }

                this.destroyer.hide();
            },

            // Remove the item, destroy the model.
            clear: function(event) {
                if ($(event.target).parent().hasClass('rule-compount-actions')) {
                    this.model.destroy();
                }
            }

        });

        // The Application
          // ---------------

        // Our overall **RulerView** is the top-level piece of UI.
        this.RulerView = View.extend({

            // Instead of generating a new element, bind to the existing skeleton of
            // the App already present in the HTML.
            el: $("#rule_container"),

            events: {
              "click #new-rule a" : "createTerm"
            },

            initialize: function(options) {
                this.container = this.$('.rule-aggregator');
                this.terms = new TermList();
                this.storage = store;

                this.listenTo(this.terms, 'add', this.addOne);
                this.listenTo(this.terms, 'reset', this.addAll);

                this.listenTo(this.terms, 'add', $.proxy(this.onListChange, this));
                this.listenTo(this.terms, 'remove', $.proxy(this.onListChange, this));
                this.listenTo(this.terms, 'change', $.proxy(this.onListChange, this));

                this.render();

                var options = options || { useStorage: true };
                var data    = options.data || (options.useStorage ? this.storage.get('app-rules') : []);

                this.terms.reset(data.length > 0 ? data : [{
                    type: 'simple-term',
                    actions: {
                        destroy: false
                    }
                }]);
            },

            render: function() {
                // create a chosen widgets
                this.$el.find('select[data-type="chosen"]').each(function () {
                    $(this).chosen({
                        no_results_text: $(this).attr('no-result-text'),
                        max_selected_options: $(this).attr('max-selected'),
                        allow_single_deselect: $(this).attr('single-deselect')
                    });
                });
            },

            addOne: function(term) {
                if (term.get('type') == 'simple-term') {
                    var view = new TermView({ model: term, index: '[' + (this.terms.length()-1) + ']' });
                } else {
                    var view = new CompountTermView({ model: term, index: '[' + (this.terms.length()-1) + ']' });

                    this.listenTo(term.get('terms'), 'add', $.proxy(this.onListChange, this));
                    this.listenTo(term.get('terms'), 'remove', $.proxy(this.onListChange, this));
                    this.listenTo(term.get('terms'), 'change', $.proxy(this.onListChange, this));
                }

                $(view.render().el).insertBefore(this.container);
                view.onAppend();
            },

            // Add all items in the **Terms** collection at once.
            addAll: function(collection) {
                $.each(collection, $.proxy( function(index, term){
                    this.addOne(term);
                }, this))
            },

            createTerm: function(e) {
                e.preventDefault();

                this.terms.add({
                    type:     $(e.target).attr("data-type"),
                    operator: $(e.target).attr("data-value")
                });
            },

            onListChange: function(model, collection) {
                this.storage.set('app-rules', this.terms.toJSON());
            },

            toInfixNotation: function() {
                var terms = this.terms.toJSON();
                var result = {
                    expresion: [],
                    expresion_str: '',
                    maps: {}
                };

                for (var i in terms) {
                    this._termToInfix(result, terms[i], String.fromCharCode(65+ parseInt(i)));
                }

                return result;
            },

            _termToInfix: function(result, term, name) {
                if (term['type'] == 'simple-term') {
                    if (term['operator']) {
                        result.expresion_str += ' ' + term['operator'] + ' ';
                        result.expresion[result.expresion.length] = term['operator'];
                    }

                    result.expresion_str += 'Exp_' + name;
                    result.expresion[result.expresion.length] = 'Exp_' + name;

                    result.maps['Exp_' + name] = term['factor'];
                } else {
                    result.expresion_str += ' ' + term['operator'] + ' (';

                    result.expresion[result.expresion.length] = term['operator'];
                    result.expresion[result.expresion.length] = '(';

                    for (var i in term['terms']) {
                        this._termToInfix(result, term['terms'][i], name + i);
                    }

                    result.expresion_str += ')';
                    result.expresion[result.expresion.length] = ')';
                }
            }
        });
    })();
});