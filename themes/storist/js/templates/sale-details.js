var SaleDetailTemplate  =   '<div class="sale-details">\
                                {{#each .}}\
                                    <div class="columns sales-detail is-marginless is-target">\
                                        <div class="column">\
                                            <span class="english">{{title}}</span><br /><span class="chinese subtitle is-6">{{chinese}}</span>\
                                        </div>\
                                        <div class="column is-narrow">\
                                            {{quantity}}\
                                        </div>\
                                        <div class="column is-2 has-text-right">\
                                            ${{amount}}\
                                        </div>\
                                    </div>\
                                {{/each}}\
                            </div>',
    SaleDetail          =   function(data)
    {
        this.tpl        =   Handlebars.compile(SaleDetailTemplate);
        this.html       =   this.tpl(data);

        return $(this.html);
    };
