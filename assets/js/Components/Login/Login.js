import React from 'react';
import PropTypes from 'prop-types';
import Avatar from '@material-ui/core/Avatar';
import Button from '@material-ui/core/Button';
import CssBaseline from '@material-ui/core/CssBaseline';
import FormControl from '@material-ui/core/FormControl';
import Input from '@material-ui/core/Input';
import InputLabel from '@material-ui/core/InputLabel';
import LockIcon from '@material-ui/icons/LockOutlined';
import Paper from '@material-ui/core/Paper';
import Typography from '@material-ui/core/Typography';
import TextField from '@material-ui/core/TextField';
import withStyles from '@material-ui/core/styles/withStyles';
import axios from './../../axios';
import { withSnackbar } from 'notistack';
import LoginForm from './LoginForm';

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

class Login extends React.Component {
    constructor(props) {
        super(props);

        const { onPresentSnackbar } = this.props;
        this.state = {
            name: '',
            password: '',
            showPassword: false,
            onPresentSnackbar: onPresentSnackbar
        };
        this.handleNameChange = this.handleNameChange.bind(this);
        this.handlePasswordChange = this.handlePasswordChange.bind(this);
        this.handleClickShowPassword = this.handleClickShowPassword.bind(this);
        this.handleSubmit = this.handleSubmit.bind(this);
    }

    handleNameChange(state) {
        this.setState({
            name: state.target.value,
        })
    }

    handlePasswordChange(state) {
        this.setState({
            password: state.target.value
        })
    }

    handleMouseDownPassword(event) {
        event.preventDefault();
    };

    handleClickShowPassword() {
        this.setState(state => ({showPassword: !state.showPassword}));
    };

    handleSubmit(e, a) {
d
        // e.preventDefault();
        // axios.post('oauth/v2/token', {
        //     "grant_type": "password",
        //     "client_id": "1_17b58gtuwp40gg8s4g800kg8gsc4gg04w8cwooko8go484sws4",
        //     "client_secret": "bw3gxccdi5w8w8owwggs8gsg8kw0088gk0wc48cwossokk0s0",
        //     "username": this.state.name,
        //     "password": this.state.password
        // }).then((response) => {
        //     localStorage.setItem('access_token', response.data.access_token);
        //     localStorage.setItem('refresh_token', response.data.refresh_token);
        //     // this.state.onPresentSnackbar('success', 'Successfully Login');
        // }).catch((e) => {
        //     this.state.onPresentSnackbar('error', 'Złe hasło albo nazwa użytkownika');
        // });
    }

    render() {
        const classes = this.props.classes;
        return (
            <React.Fragment>
                <CssBaseline/>
                <main className={classes.layout}>
                    <Paper className={classes.paper}>
                        <Avatar className={classes.avatar}>
                            <LockIcon/>
                        </Avatar>
                        <Typography variant="headline">Zaloguj się</Typography>
                        <LoginForm />
                        {/*<form className={classes.form} onSubmit={this.handleSubmit}>*/}
                            {/*<FormControl margin="normal" required fullWidth>*/}
                                {/*<InputLabel  htmlFor="username">Nazwa Użytkownika</InputLabel>*/}
                                {/*<Input  id="username" name="text" autoComplete="text" value={this.state.name}*/}
                                       {/*onChange={this.handleNameChange} autoFocus/>*/}
                                {/*<TextField helperText="test" />*/}
                            {/*</FormControl>*/}
                            {/*<FormControl margin="normal" required fullWidth>*/}
                                {/*<InputLabel htmlFor="password">Hasło</InputLabel>*/}
                                {/*<Input*/}
                                    {/*name="password"*/}
                                    {/*type="password"*/}
                                    {/*id="password"*/}
                                    {/*autoComplete="current-password"*/}
                                    {/*value={this.state.password}*/}
                                    {/*onChange={this.handlePasswordChange}*/}
                                {/*/>*/}
                            {/*</FormControl>*/}

                            {/*<Button*/}
                                {/*size="small" className={classes.button}*/}
                                {/*fullWidth*/}
                            {/*>*/}
                                {/*Zapomniałem hasła*/}
                            {/*</Button>*/}
                        {/*</form>*/}
                    </Paper>
                </main>
            </React.Fragment>
        );
    }
}

export default withStyles(styles)(withSnackbar(Login));