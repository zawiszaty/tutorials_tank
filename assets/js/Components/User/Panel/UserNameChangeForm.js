import React from 'react'
import {Field, reduxForm} from 'redux-form'
import TextField from '@material-ui/core/TextField';
import PropTypes from 'prop-types';
import Button from "@material-ui/core/Button/Button";
import Paper from "@material-ui/core/Paper/Paper";
import withStyles from '@material-ui/core/styles/withStyles';
import FormControl from '@material-ui/core/FormControl';
import {store} from './../../../store';
import {connect} from 'react-redux';
import axios from "../../../axios";
import {withSnackbar} from 'notistack';

const styles = theme => ({
    layout: {
        width: 'auto',
        display: 'block', // Fix IE11 issue.
        marginLeft: theme.spacing.unit * 3,
        marginRight: theme.spacing.unit * 3,
        [theme.breakpoints.up(400 + theme.spacing.unit * 3 * 2)]: {
            width: 400,
            marginLeft: 'auto',
            marginRight: 'auto',
        },
    },
    paper: {
        marginTop: theme.spacing.unit * 8,
        display: 'flex',
        flexDirection: 'column',
        alignItems: 'center',
        padding: `${theme.spacing.unit * 2}px ${theme.spacing.unit * 3}px ${theme.spacing.unit * 3}px`,
    },
    avatar: {
        margin: theme.spacing.unit,
        backgroundColor: theme.palette.secondary.main,
    },
    form: {
        width: '100%', // Fix IE11 issue.
        marginTop: theme.spacing.unit,
    },
    submit: {
        marginTop: theme.spacing.unit * 3,
    },
});

const validate = values => {
    const errors = {}
    if (!values.username) {
        errors.username = 'Pole nie może być puste'
    }
    if (!values.password) {
        errors.password = 'Pole nie może być puste'
    }

    return errors
}

const warn = values => {
    const warnings = {}
    if (values.username < 19) {
        warnings.age = 'Hmm, you seem a bit young...'
    }
    return warnings
}

const renderTextField = (
    {input, label, meta, ...custom, type},
) => (
    <FormControl margin="normal" required fullWidth>
        <TextField
            error={meta.error && meta.touched}
            label={label}
            helperText={meta.touched && meta.error}
            {...input}
            type={type}
            required
        />
    </FormControl>
);
// hintText={label}
// floatingLabelText={label}
// errorText={touched && error}
const SyncValidationForm = (props) => {
    const {handleSubmit, pristine, reset, submitting, classes, onPresentSnackbar, user, onLoginUser} = props
    return (
        <form className={classes.form} onSubmit={handleSubmit(val => {
            axios.post('/api/v1/user/change/username', {'name': val.username}, {
                headers: {'Authorization': 'Bearer ' + localStorage.getItem('token')}
            }).then((response) => {
                onPresentSnackbar('success', 'Zmienione nazwe');
            }).catch((e) => {
                onPresentSnackbar('error', 'Coś poszło nie tak');
            })
        })}>
                <Field
                    id="username"
                    name="username"
                    component={renderTextField}
                    label="Nazwa użytkownika"
                    type="text"
                />
                <Button
                    type="submit"
                    fullWidth
                    variant="raised"
                    color="primary"
                >
                    Zmień Nazwę
                </Button>
        </form>
    )
}
const mapStateToProps = state => ({
    initialValues: {
        username: state.user.name
    }
});

const mapActionToProps = {
};

export default connect(mapStateToProps, mapActionToProps)(withStyles(styles)(reduxForm({
    form: 'syncValidation',  // a unique identifier for this form
    validate,                // <--- validation function given to redux-form
    warn                     // <--- warning function given to redux-form
})(withSnackbar(SyncValidationForm))))