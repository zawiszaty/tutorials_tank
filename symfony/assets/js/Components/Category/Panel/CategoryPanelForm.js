import React from 'react';
import withStyles from '@material-ui/core/styles/withStyles';
import TextField from '@material-ui/core/TextField';
import debounce from 'lodash/debounce';

const styles = {};

class CategoryPanelForm extends React.Component {
    constructor(props) {
        super(props);
        this.state = {
          query: props.query
        };
    }

    handleChange = (e) => {
        this.setState({
            query: e
        });
        this.props.handleChange(e)
    };

    onSearchItemWithDebounce = debounce((query) => {
        this.setState({
            query
        }, () => {
            //Do Stuff after state is updated.
            this.props.handleChange(query);
            console.log('search with Debounce: ', this.state.query);
        })
    }, 1000);


    render() {
        const classes = this.props.classes;
        return (
            <React.Fragment>
                <input type="text"  value={this.state.query} onChange={(e) => this.onSearchItemWithDebounce(e.target.value)} />
            </React.Fragment>
        );
    }
}

export default withStyles(styles)(CategoryPanelForm);