import React from 'react';
import withStyles from '@material-ui/core/styles/withStyles';
import TextField from '@material-ui/core/TextField';
import debounce from 'lodash/debounce';

const styles = {};

class CategoryPanelForm extends React.Component {
    constructor(props) {
        super(props);

        this.handleChange = this.handleChange.bind(this);

    }

    handleChange(e) {
        this.props.handleChange(e)
    }



    render() {
        const classes = this.props.classes;
        return (
            <React.Fragment>
                <TextField value={this.props.query}
                           onChange={e => this.handleChange(e.target.value)}
                           label="Szukaj"
                />
            </React.Fragment>
        );
    }
}

export default withStyles(styles)(CategoryPanelForm);