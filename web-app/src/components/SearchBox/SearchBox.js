import React from 'react';
import Button from '@material-ui/core/Button';
import {ValidatorForm, TextValidator} from 'react-material-ui-form-validator';
import withStyles from "@material-ui/core/es/styles/withStyles";
import CircularProgress from "@material-ui/core/CircularProgress";
import classNames from 'classnames';
import green from '@material-ui/core/colors/green';
import {login} from "../../actions/user";
import {connect} from "react-redux";
import TextField from "@material-ui/core/TextField";

const styles = theme => ({
    textField: {
        margin: "auto",
        width: '100%'
    }
});

class SearchBox extends React.Component {
    constructor(props) {
        super(props);
    }

    render() {
        const {classes} = this.props;

        return (
            <React.Fragment>
                <form onSubmit={() => {
                    console.log('zdiala');
                    this.props.getAll();
                }}>
                    <TextField
                        id="standard-name"
                        label="Szukaj"
                        className={classes.textField}
                        value={this.props.query}
                        onChange={this.props.handleChangeQuery}
                        variant="outlined"
                        margin="normal"
                    />
                    <Button variant="contained" fullWidth type="submit">
                        Szukaj
                    </Button>
                </form>
            </React.Fragment>
        )
    }
}

const mapStateToProps = (state) => {
    return {
        user: state.user
    }
};
const mapDispatchToProps = {login};

export default connect(mapStateToProps, mapDispatchToProps)(withStyles(styles)(SearchBox));
