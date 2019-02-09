import React from 'react'
import {Field, reduxForm} from 'redux-form'
import TextField from '@material-ui/core/TextField';
import Button from "@material-ui/core/Button/Button";
import withStyles from '@material-ui/core/styles/withStyles';
import FormControl from '@material-ui/core/FormControl';
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
    const errors = {};
    if (!values.oldPassword) {
        errors.oldPassword = 'Pole nie może być puste'
    }
    if (!values.password_first) {
        errors.password_first = 'Pole nie może być puste'
    }
    if (!values.password_second) {
        errors.password_second = 'Pole nie może być puste'
    }
    if (values.password_first !== values.password_second) {
        errors.password_first = 'Hasła muszą być identyczne'
    }
    return errors
};

const warn = values => {
    const warnings = {};
    if (values.username < 19) {
        warnings.age = 'Hmm, you seem a bit young...'
    }
    return warnings
};

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
    const {handleSubmit, pristine, reset, submitting, classes, onPresentSnackbar, user, onLoginUser} = props;
    return (
        <form className={classes.form} onSubmit={handleSubmit(val => {
            axios.post('/api/v1/user/change/password', {
                'oldPassword': val.oldPassword,
                'plainPassword': {
                    'first': val.password_first,
                    'second': val.password_second
                }
            }, {
                headers: {'Authorization': 'Bearer ' + localStorage.getItem('token')}
            }).then((response) => {
                onPresentSnackbar('success', 'Hasło zmieniono');
            }).catch((e) => {
                onPresentSnackbar('error', 'Coś poszło nie tak');
            })
        })}>
            <Field
                id="oldPassword"
                name="oldPassword"
                component={renderTextField}
                label="Obecne hasło"
                type="password"
            />
            <Field
                id="password_first"
                name="password_first"
                component={renderTextField}
                label="Hasło"
                type="password"
            />
            <Field
                id="password_second"
                name="password_second"
                component={renderTextField}
                label="Powtórz"
                type="password"
            />
            <Button
                type="submit"
                fullWidth
                variant="raised"
                color="primary"
            >
                Zmień Hasło
            </Button>
        </form>
    )
};
const mapStateToProps = state => ({});

const mapActionToProps = {};

export default connect(mapStateToProps, mapActionToProps)(withStyles(styles)(reduxForm({
    form: 'syncValidationPassword',  // a unique identifier for this form
    validate,                // <--- validation function given to redux-form
    warn                     // <--- warning function given to redux-form
})(withSnackbar(SyncValidationForm))))