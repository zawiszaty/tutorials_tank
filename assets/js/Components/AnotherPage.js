import React from 'react';
import Button from '@material-ui/core/Button';

class AnotherPage extends React.Component {
    constructor(props) {
        super(props);
    }

    render() {
        return (
            <div>
                <p>Welcome to another page!</p>
                <Button variant="contained" color="primary">
                    Hello World
                </Button>

                {console.log(this.props.children)}
            </div>
        );
    }
}

export default AnotherPage;