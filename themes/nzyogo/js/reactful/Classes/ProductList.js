class ProductList extends React.Component
{
    render()
    {
        var products = this.props.data.map(function(item) {
			return (
				<Product className="Site-item" key={item.id}>
					{item.title}
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
