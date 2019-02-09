import React from 'react'
import {Field, reduxForm} from 'redux-form'
import TextField from '@material-ui/core/TextField';
import Button from "@material-ui/core/Button/Button";
import withStyles from '@material-ui/core/styles/withStyles';
import FormControl from '@material-ui/core/FormControl';
import {connect} from 'react-redux';
import {registerUser, registerUserSuccess} from './../../actions/user-action'
import {withSnackbar} from 'notistack';
import axios from "../../axios";
import {Redirect} from 'react-router'

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
    if (!values.username) {
        errors.username = 'Pole nie może być puste'
    } else if (values.username.length > 15) {
        errors.username = 'Must be 15 characters or less'
    }
    if (!values.email) {
        errors.email = 'Pole nie może być puste'
    }
    if (!values.password_first) {
        errors.password_first = 'Pole nie może być puste'
    }
    if (values.password_first !== values.password_second) {
        errors.password_first = 'Hasła musza być identyczne'
    }
    if (!values.password_second) {
        errors.password_second = 'Pole nie może być puste'
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
    const {handleSubmit, pristine, reset, submitting, classes, onPresentSnackbar} = props;
    return (
        <form className={classes.form} onSubmit={handleSubmit(val => {
            axios.post('api/v1/user/register', {
                username: val.username,
                email: val.email,
                plainPassword: {
                    first: val.password_first,
                    second: val.password_second
                }
            }).then(response => {
                    onPresentSnackbar('success', 'Zarejestrowano.');
                    return (<Redirect to="/user/potwierdz-konto" />)
                }
            ).catch(error => {
                onPresentSnackbar('error', 'Coś poszło nie tak.')
            })
        })}>
            <div>
                {props.isLoaded}
                <Field
                    id="username"
                    name="username"
                    component={renderTextField}
                    label="Nazwa użytkownika"
                    type="text"
                />
                <Field
                    id="email"
                    name="email"
                    component={renderTextField}
                    label="Email"
                    type="email"
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
                    label="Powtórz hasło"
                    type="password"
                />
            </div>
            <div>
                <Button
                    type="submit"
                    fullWidth
                    variant="raised"
                    color="primary"
                >
                    Zaloguj
                </Button>
            </div>
        </form>
    )
};
const mapStateToProps = state => ({
    user: state.user,
    isLoaded: state.isLoaded
});

const mapActionToProps = {
    onRegisterUser: registerUser,
    onRegisterUserSuccess: registerUserSuccess,

};

export default connect(mapStateToProps, mapActionToProps)(withStyles(styles)(reduxForm({
    form: 'syncValidation',  // a unique identifier for this form
    validate,                // <--- validation function given to redux-form
    warn                     // <--- warning function given to redux-form
})(withSnackbar(SyncValidationForm))))