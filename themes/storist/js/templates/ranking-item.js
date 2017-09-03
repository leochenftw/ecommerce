var TemplateRankingItem =   '<div data-barcode="{{Barcode}}" class="columns sales-record is-marginless">\
                                <div class="column is-3">{{Barcode}}</div>\
                                <div class="column">{{Title}} | {{Chinese}}</div>\
                                <div class="column has-text-centered is-2">{{Provider}}</div>\
                                <div class="column has-text-centered is-1">{{Sales}}</div>\
                                <div class="column has-text-centered is-1">{{Quantity}}</div>\
                            </div>',
    TemplateRankingList =   '{{#each .}}\
                            <div data-barcode="{{Barcode}}" class="columns sales-record is-marginless">\
                                <div class="column is-3">{{Barcode}}</div>\
                                <div class="column">{{Title}} | {{Chinese}}</div>\
                                <div class="column has-text-centered is-2">{{Provider}}</div>\
                                <div class="column has-text-centered is-1">{{Sales}}</div>\
                                <div class="column has-text-centered is-1">{{Quantity}}</div>\
                            </div>\
                            {{/each}}',
    RankingList         =   function(data)
    {
        this.tpl        =   Handlebars.compile(TemplateRankingList);
        this.html       =   this.tpl(data);

        return $(this.html);
    };
