class Product extends React.Component
{
    render()
    {
        return (
            <li class="product-item" key={this.props.prod_id}>
                {this.props.title}
            </li>
        );
    }
}
