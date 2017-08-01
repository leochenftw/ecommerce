var TemplateProductRows =   '{{#each @root}}\
                                <div class="columns product-row is-marginless is-temp is-target" data-id="{{id}}" data-title="{{title}}" data-chinese="{{chinese_title}}" data-cost="{{cost}}" data-price="{{price}}" data-stock="{{ data.stock_count}}" data-barcode="{{barcode}}" data-stock-count="{{stock_count}}" data-width="{{width}}" data-height="{{height}}" data-depth="{{depth}}" data-measurement="{{measurement}}" data-weight="{{weight}}" data-manufacturer="{{manufacturer}}">\
                                    <div class="column product-row__thumbnail is-1"></div>\
                                    <div class="column product-row__title"><span class="english">{{title}}</span><br /><span class="chinese subtitle is-6">{{chinese_title}}</span></div>\
                                    <div class="column product-row__stock-count is-1 has-text-centered">{{stock_count}}</div>\
                                    <div class="column product-row__cost has-text-centered is-2">{{cost}}</div>\
                                    <div class="column product-row__price has-text-centered is-2">{{price}}</div>\
                                    <div class="column product-row__last-update is-2">{{last_update}}</div>\
                                </div>\
                            {{/each}}';
