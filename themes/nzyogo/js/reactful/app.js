window.listYpos = 0;
class Pricing extends React.Component
{
    constructor(props)
    {
        super(props);
        this.state = {
            cost: this.props.data ? this.props.data.cost : '',
            price: this.props.data ? this.props.data.price : '',
            prod_id: this.props.prod_id
        };
    }

    componentWillMount()
    {
        this.setState({
            cost: this.props.data ? this.props.data.cost : '',
            price: this.props.data ? this.props.data.price : '',
            prod_id: this.props.prod_id
        });

        if (this.props.variant_id) {
            this.setState({
                variant_id: this.props.variant_id
            });
        }
    }

    onChangeHandler(e)
    {
        switch (e.target.name) {
            case 'cost':
                this.setState({
                    cost: e.target.value
                });
                break;
            default:
                this.setState({
                    price: e.target.value
                });
                break;
        }
    }

    onSubmit(e)
    {
        e.preventDefault();
        if (this.state.cost.trim().length == 0 || this.state.price.trim().length == 0) {
            alert('missing values')
            return;
        }
        let endpoint = $(e.target).attr('action');
        $.post(endpoint, this.state, function(response)
        {
            trace(response);
            window.main.setState({
                show_form: true,
                product: null
            }, function()
            {
                window.main.setState({
                    show_form: true,
                    product: response
                });
                $('body').addClass('form-mode');
            });
        });
    }

    deletePricing(e)
    {
        e.preventDefault();

        if (confirm('deleting it?')) {
            let endpoint = $(e.target).parents('form:eq(0)').attr('action'),
                prod_id = JSON.stringify(this.state);
            $.ajax({
                url: endpoint,
                type: 'DELETE',
                data: prod_id,
                cache: false,
                contentType: false,
                processData: false
            }).always(function(response) {

                window.main.setState({
                    show_form: true,
                    product: null
                }, function()
                {
                    window.main.setState({
                        show_form: true,
                        product: response
                    });

                    $('body').addClass('form-mode');
                });
            });
        }
    }

    render()
    {
        let lastColumn = null;
        let btnDel = null;
        let action = '/api/v/1/pricings/' + (this.props.data && this.props.data.pricing_id ? this.props.data.pricing_id : '');
        let buttonClass = 'button' + (this.props.data && this.props.data.pricing_id ? ' hide' : '');
        if (this.props.data) {
            var id = 'created-' + this.props.data.pricing_id;
            lastColumn = (
                <div className="field created-wrapper">
                    <label htmlFor="created"> </label>
                    <input id={id} type="text" className="text" readOnly={true} value={this.props.data.created} />
                </div>
            );
            btnDel = (
                <button onClick={this.deletePricing.bind(this)} type="button" className="button icon-x">删除</button>
            );
        } else {
            lastColumn = '';
            btnDel = '';
        }

        return (
            <form method="post" action={action} className={this.props.data && this.props.data.pricing_id ? "pricing-form as-flex vertical-bottom pricing loaded" : "pricing-form as-flex vertical-bottom pricing empty"} onSubmit={this.onSubmit.bind(this)}>
                <div className="field new-cost-wrapper">
                    <label htmlFor={this.props.data ? ('cost-' + this.props.data.pricing_id) : 'new-cost'}>进价</label>
                    <input name="cost" onChange={this.onChangeHandler.bind(this)} id={this.props.data ? ('cost-' + this.props.data.pricing_id) : 'new-cost'} type="text" className="text" value={this.state.cost} />
                </div>
                <div className="field new-price-wrapper">
                    <label htmlFor={this.props.data ? ('price-' + this.props.data.pricing_id) : 'new-price'}>售价</label>
                    <input name="price" onChange={this.onChangeHandler.bind(this)} id={this.props.data ? ('price-' + this.props.data.pricing_id) : 'new-price'} type="text" className="text" value={this.state.price} />
                </div>
                {lastColumn}
                {btnDel}
                <div className="field new-button-wrapper">
                    <input type="submit" className={buttonClass} value="加一个" />
                </div>
            </form>
        );
    }
}

class ImageUplader extends React.Component
{
    onSubmit(e)
    {
        e.preventDefault();
        let len = $(e.target).find('input[type="file"]').val().length;
        if (len == 0) {
            alert('nothing to upload');
            return;
        }
        let formData    =   new FormData(e.target),
            self        =   this;
        console.log('uploading...');
        $.ajax({
            url: '/storist/v1/photos/UploadHandler',
            type: 'POST',
            data: formData,
            cache: false,
            contentType: false,
            processData: false,
            success: function(response) {
                alert('upload completed');
            },
            error: function(response) {
                console.log(response);
            }
        });
    }

    render()
    {
        let attachTo = this.props.variant_id ? 'VariantPhotoID' : 'ProductPhotoID';
        let id = this.props.variant_id ? this.props.variant_id : this.props.prod_id;

        return (
            <form action="storist/v1/photos/UploadHandler" method="post" encType="multipart/form-data" onSubmit={this.onSubmit.bind(this)} className="add-one-wrapper as-flex wrap variant vertical-bottom">
                <input type="file" accept="image/*" name="photos[]" />
                <input type="hidden" name="attach_to" value={attachTo} />
                <input type="hidden" name="ref_id" value={id} />
                <input type="submit" className="button" value="贴" />
            </form>
        );
    }
}

class Pricings extends React.Component
{
    constructor(props)
    {
        super(props);
        this.state = {
            data: null
        };
        window.pricings = this;
    }

    componentWillUpdate(nextProps, nextState)
    {
        // console.log(nextProps);
        // console.log(nextState);
    }

    componentWillReceiveProps(nextProps, nextState)
    {
        this.setState({data: nextProps.data});
    }

    componentWillMount()
    {
        // trace(this.props.data);
        this.setState({data: this.props.data});
    }

    render()
    {
        let self = this;
        let rows = !this.state.data ? null : this.state.data.map(function(item, i) {
            item.cost = item.cost.toFloat().toFixed(2);
            item.price = item.price.toFloat().toFixed(2);
            return (
                self.props.variant_id ? <Pricing key={i} variant_id={self.props.variant_id} prod_id={self.props.prod_id} data={item} /> : <Pricing key={i} prod_id={self.props.prod_id} data={item} />
            );
        });

        let firstRow = this.props.variant_id ? <Pricing variant_id={this.props.variant_id} prod_id={self.props.prod_id} data={null} /> : <Pricing prod_id={this.props.prod_id} data={null} />;
        let uploader = <ImageUplader prod_id={this.props.prod_id} variant_id={this.props.variant_id} />;

        return (
            <div id="txt-pricing-row">
                {uploader}
                {firstRow}
                {rows}
            </div>
        );
    }
}

class Variant extends React.Component
{
    constructor(props)
    {
        super(props);
        this.state = {
            data: null
        };
    }

    componentWillReceiveProps(nextProps, nextState)
    {
        this.setState({data: nextProps.data});
    }

    componentWillMount()
    {
        this.setState({prod_id: this.props.prod_id, data: this.props.data});
    }

    onChangeHandler()
    {

    }

    render()
    {
        let var_id = this.state.data.variant_id;
        let id = 'variant-title-' + this.state.data.variant_id;

        return (
            <div className="variant as-flex wrap">
                <div className="field variant-title">
                    <label htmlFor={id}>类名</label>
                    <input onChange={this.onChangeHandler.bind(this)} id={id} type="text" className="text" value={this.state.data.title ? this.state.data.title : ''} />
                </div>
                <div className="variant-pricings">
                    <Pricings data={this.state.data.pricings} prod_id={this.state.prod_id} variant_id={var_id} />
                </div>
            </div>
        );
    }
}

class Variants extends React.Component
{
    constructor(props)
    {
        super(props);
        this.state = {
            title: '',
            cost: '',
            price: '',
            prod_id: null,
            data: null
        };
    }

    componentWillReceiveProps(nextProps, nextState)
    {
        this.setState({data: nextProps.data});
    }

    componentWillMount()
    {
        this.setState({prod_id: this.props.prod_id, data: this.props.data});
    }

    onChangeHandler(e)
    {
        switch (e.target.name)
        {
            case 'new-variant-title':
                this.setState({title: e.target.value});
                break;
            case 'new-variant-cost':
                this.setState({cost: e.target.value});
                break;
            case 'new-variant-price':
                this.setState({price: e.target.value});
                break;
        }
    }

    onSubmit(e)
    {
        e.preventDefault();
        let endpoint    =   $(e.target).attr('action'),
            post_vars   =   {title: this.state.title, cost: this.state.cost, price: this.state.price, prod_id: this.state.prod_id};
        $.post(endpoint, post_vars, function(response)
        {
            trace(response);
            window.main.setState({
                show_form: true,
                product: null
            }, function()
            {
                window.main.setState({
                    show_form: true,
                    product: response
                });

                $('body').addClass('form-mode');
            });
        });
    }

    render()
    {
        let prod_id = this.props.prod_id;
        let variants = !this.state.data ? null : this.state.data.map(function(item, i) {
            return (
                <Variant key={i} prod_id={prod_id} data={item} />
            );
        });
        return (
            <div id="variants" className="fields">
                <h2 className="hide">Variants</h2>
                <form action="api/v/1/variants" onSubmit={this.onSubmit.bind(this)} className="add-one-wrapper as-flex wrap variant vertical-bottom">
                    <div className="field variant-title">
                        <label htmlFor="new-variant-title">类名</label>
                        <input onChange={this.onChangeHandler.bind(this)} id="new-variant-title" name="new-variant-title" type="text" className="text" value={this.state.title} />
                    </div>
                    <div className="field variant-cost">
                        <label htmlFor="new-variant-cost">进价</label>
                        <input onChange={this.onChangeHandler.bind(this)} id="new-variant-cost" name="new-variant-cost" type="text" className="text" value={this.state.cost} />
                    </div>
                    <div className="field variant-price">
                        <label htmlFor="new-variant-price">售价</label>
                        <input onChange={this.onChangeHandler.bind(this)} id="new-variant-price" name="new-variant-price" type="text" className="text" value={this.state.price} />
                    </div>
                    <div className="field variant-button">
                        <button className="button">加一个</button>
                    </div>
                </form>
                {variants}
            </div>
        );
    }
}

class ProductDetail extends React.Component
{
    constructor(props)
    {
        super(props);
        this.state = {
            product_id: '',
            title: '',
            alias: '',
            barcode: '',
            content: '',
            varianted: false,
            button_label: 'Submit',
            pricings: [],
            variants: [],
            SecurityID: window.security_id
        };
    }

    componentWillReceiveProps(nextProps, nextState)
    {
        console.log(nextProps.data);
        var varianted = (nextProps.data.variants && nextProps.data.variants.length > 0) ? true : false;
        var self = this;
        this.setState({
            product_id: nextProps.data.product_id,
            title: nextProps.data.title,
            alias: nextProps.data.alias,
            barcode: nextProps.data.barcode,
            content: nextProps.data.content,
            varianted: varianted,
            pricings: nextProps.data.pricings,
            variants: nextProps.data.variants
        }, function(){
            tinymce.activeEditor.setContent(nextProps.data.content ? nextProps.data.content : '');
        });

    }

    componentWillMount()
    {
        var varianted = (this.props.data.variants && this.props.data.variants.length > 0) ? true : false;
        this.setState({
            product_id: this.props.data.product_id,
            title: this.props.data.title,
            alias: this.props.data.alias ? this.props.data.alias : '',
            barcode: this.props.data.barcode ? this.props.data.barcode : '',
            content: this.props.data.content ? this.props.data.content : '',
            varianted: varianted,
            pricings: this.props.data.pricings,
            variants: this.props.data.variants
        }, function(){
            tinymce.activeEditor.setContent(this.props.data.content ? this.props.data.content : '');
        });
    }

    componentDidMount()
    {
        var self = this;
        tinymce.init({ selector:'#txt-content', height: 400, menubar: false });
        tinymce.activeEditor.on('keyup', function(e) {
            var content = tinymce.activeEditor.getContent({format : 'raw'});
            self.setState({content: content});
        });
        window.listYpos = $(window).scrollTop();
        $(window).scrollTop(0);
    }

    componentWillUnmount()
    {
        tinymce.remove('#txt-content');
        $(window).scrollTop(window.listYpos);
    }

    onChangeHandler(e)
    {
        this.setState({varianted: e.target.value == 1 ? true : false});
    }

    onTextChange(e)
    {
        if ($(e.target).is('#txt-alias')) {
            this.setState({alias: e.target.value});
        }

        if ($(e.target).is('#txt-title')) {
            this.setState({title: e.target.value});
        }

        if (e.target.name == 'Barcode') {
            this.setState({barcode: e.target.value});
        }

    }

    onSubmit(e)
    {
        e.preventDefault();
        var self = this;
        $.post('/api/v/1/products/' + (self.state.product_id ? self.state.product_id : ''), self.state, function(data) {
            window.security_id = data.security_id;

            if (!self.state.product_id) {
                var new_product = {
                    product_alias:  data.product.alias,
                    product_id:     data.product.product_id,
                    product_title:  data.product.title
                };
                window.all_products.unshift(new_product);
            } else {
                window.all_products.map(function(item)
                {
                    if (item.product_id === data.product.product_id) {
                        item.product_title = data.product.title;
                        item.product_alias = data.product.alias;
                    }
                });
            }

            self.setState({
                product_id: data.product.product_id,
                varianted: false,
                pricings: data.product.pricings,
                variants: data.product.variants,
                SecurityID: window.security_id
            });

            window.header.setState(
            {
                data: window.all_products,
                active_product_id: data.product.product_id
            });
        });
    }

    closeProductDetails(e)
    {
        e.preventDefault();
        window.main.setState({
            show_form: false,
            product: null
        });
        window.header.setState({
            active_product_id: null
        });

        $('body').removeClass('form-mode');
    }

    deleteProduct(e)
    {
        e.preventDefault();
        var product_id = this.state.product_id,
            security_id = this.state.SecurityID;
        if (confirm('sure?')) {
            window.main.setState({
                show_form: false,
                product: null
            });
            window.header.setState({
                active_product_id: null
            });

            $('body').removeClass('form-mode');

            $.ajax({
                url: '/api/v/1/products/' + product_id,
                type: 'DELETE',
                data: security_id,
                cache: false,
                contentType: false,
                processData: false
            }).always(function(response) {
                var findings = window.all_products.filter(function(item, i) {
                    return item.product_id !== product_id;
                });
                window.all_products = findings;
                window.header.setState({
                    data: window.all_products
                });
			});
        }
    }

    render()
    {
        let single_price = <Pricings prod_id={this.state.product_id} data={this.state.pricings} />;
        let variants = <Variants prod_id={this.state.product_id} data={this.state.variants} />;
        let pricing = this.state.varianted ? variants : single_price;
        let btnDelete = this.state.product_id ? <button onClick={this.deleteProduct.bind(this)} type="button" className="button">Delete</button> : '';

        let appendix = '';
        if (this.state.product_id) {
            appendix = (
                <div className="form-pricings-variants">
                    <div id="cb-varianted-wrapper" className="field">
                        <label className="variant-label" htmlFor="not-varianted"><input id="not-varianted" type="radio" onChange={this.onChangeHandler.bind(this)} checked={this.state.varianted === false} className="radio" name="has-variant" value="0" /> 单一产品</label>
                        <label className="variant-label" htmlFor="is-varianted"><input id="is-varianted" type="radio" onChange={this.onChangeHandler.bind(this)} checked={this.state.varianted === true} className="radio" name="has-variant" value="1" /> 分类产品</label>
                    </div>
                    {pricing}
                </div>
            );
        }

        return (
            <section className="container">
                <form id="form-product" method="post" action={this.state.product_id ? "/api/v/1/products/" +  this.state.product_id : "/api/v/1/products"} onSubmit={this.onSubmit.bind(this)}>
                    <h2 className="as-flex wrap justify vertical-center">
                        <span className="as-block">{this.state.product_id ? '修改商品' : '添加商品'}</span>
                        <button type="button" className="icon-x" onClick={this.closeProductDetails.bind(this)}>Close</button>
                    </h2>
                    <div id="txt-title-row" className="field">
                        <label htmlFor="txt-title" className="as-block">品名</label>
                        <input onChange={this.onTextChange.bind(this)} className="text" id="txt-title" type="text" name="Title" value={this.state.title ? this.state.title : ''} />
                    </div>
                    <div id="txt-alias-row" className="field">
                        <label htmlFor="txt-alias" className="as-block">Alias</label>
                        <input onChange={this.onTextChange.bind(this)} className="text" id="txt-alias" type="text" name="Alias" value={this.state.alias ? this.state.alias : ''} />
                    </div>
                    <div id="txt-barcode-row" className="field">
                        <label htmlFor="txt-barcode" className="as-block">Barcode</label>
                        <input onChange={this.onTextChange.bind(this)} className="text" id="txt-barcode" type="text" name="Barcode" value={this.state.barcode ? this.state.barcode : ''} />
                    </div>
                    <div id="txt-content-row" className="field">
                        <label htmlFor="txt-content" className="as-block">Content</label>
                        <textarea onChange={this.onTextChange.bind(this)} className="text" id="txt-content" type="text" name="Content" value={this.state.content ? this.state.content : ''}></textarea>
                    </div>
                    <div className="Actions as-flex justify">
                        {btnDelete}
                        <input type="submit" className="button" value={this.state.button_label} />
                    </div>
                </form>
                {appendix}
            </section>
        );
    }
}

class Product extends React.Component
{
    render()
    {
        return (
            <li className={this.props.className} onClick={this.props.clickEvent.bind(this.props.clickTarget, this.props.children.product_id)}>
                {this.props.children.product_title}
            </li>
        );
    }
}

class ProductList extends React.Component
{
    constructor(props)
    {
        super(props);
        window.header = this;
        this.state = {
            data: this.props.data,
            active_product_id: null
        };
    }

    clickHandler(product_id)
    {
        if (this.state.active_product_id !== product_id) {
            this.setState({active_product_id: product_id});
            window.main.setState({
                show_form: false,
                product: null
            });

            $.get('/api/v/1/products/' + product_id, function(product) {
                window.main.setState({
                    show_form: true,
                    product: product
                });

                $('body').addClass('form-mode');
            });
        } else {
            console.warn('same product');
        }
    }

    componentWillMount()
    {
        this.setState({data: this.props.data, active_product_id: null});
    }

    componentWillReceiveProps(nextProps, nextState)
    {
        this.setState({data: nextProps.data});
    }

    render()
    {
        let self = this;
        var products = this.state.data.map(function(item) {
            var classes = 'product-item ';
            if (item.product_id === self.state.active_product_id) {
                classes += 'active';
            }
			return (
				<Product key={item.product_id} className={classes} clickEvent={self.clickHandler} clickTarget={self}>
					{item}
				</Product>
			);
		});
		return (
			<ul className="product-list">
				{products}
			</ul>
		);
    }
}

class Products extends React.Component
{
    constructor(props)
    {
        super(props);
        this.state = {
            all_products: [],
            products: []
        };
    }

    filterList(e) {
        var updatedList =   this.state.all_products,
            input       =   $.trim(e.target.value.toLowerCase());
        if (input.length > 0) {
            updatedList = updatedList.filter(function(item){
                return item.product_title.toLowerCase().indexOf(input) !== -1;
            });
        }
        this.setState({products: updatedList});
    }

    componentWillMount()
    {
        this.setState({all_products: window.all_products, products: window.all_products});
    }

    newProductForm()
    {
        window.main.setState({
            show_form: true,
            product: null
        });

        window.header.setState({
            active_product_id: null
        });

        $('body').addClass('form-mode');
    }

    render()
    {
		return (
            <div className="product-filter-wrapper">
                <div className="product-filter">
                    <input type="text" placeholder="Search" onChange={this.filterList.bind(this)} />
                    <button type="button" onClick={this.newProductForm.bind(this)}>+</button>
                </div>
                <ProductList data={this.state.products} />
            </div>
		);
    }
}

class Main extends React.Component
{
    constructor(props)
    {
        super(props);
        window.main = this;
        this.state = {
            show_form: false,
            product: null
        };

        $('body').removeClass('form-mode');
    }

    render()
    {
        if (this.state.show_form && this.state.product) {
            // trace(this.state.product);
            return <ProductDetail data={this.state.product} />;
        } else if (this.state.show_form && !this.state.product) {
            var data = [];
            return <ProductDetail data={data} />;
        }

        return null;
    }
}

ReactDOM.render(
	<Products />,
	document.getElementById('header')
);

ReactDOM.render(
	<Main />,
	document.getElementById('main')
);

function compare(a,b) {
    if (a.created > b.created)
        return -1;
    if (a.created < b.created)
        return 1;
    return 0;
}
