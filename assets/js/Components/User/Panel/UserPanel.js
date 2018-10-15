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
import axios from './../../../axios';
import {withSnackbar} from 'notistack';
import Card from '@material-ui/core/Card';
import CardActionArea from '@material-ui/core/CardActionArea';
import CardActions from '@material-ui/core/CardActions';
import CardContent from '@material-ui/core/CardContent';
import CardMedia from '@material-ui/core/CardMedia';
import Grid from '@material-ui/core/Grid';
import UserNameForm from './UserNameChangeForm';
import UserEmailForm from './UserEmailChangeForm';
import UserPasswordForm from './UserPasswordChangeForm';
import UserAvatarChangeForm from './UserAvatarChangeForm';

const styles = theme => ({
    layout: {
        width: 'auto',
        display: 'block', // Fix IE11 issue.
        marginLeft: theme.spacing.unit * 3,
        marginRight: theme.spacing.unit * 3,
        [theme.breakpoints.up(400 + theme.spacing.unit * 3 * 2)]: {
            width: '80%',
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

class UserPanel extends React.Component {
    constructor(props) {
        super(props);

        const {onPresentSnackbar} = this.props;
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
                    <Grid container className={classes.root} spacing={16}>
                        <Grid item xs={12}>
                            <Paper className={classes.paper}>
                                <Avatar className={classes.avatar}>
                                    <LockIcon/>
                                </Avatar>
                                <Typography variant="headline">Panel Użytkownika</Typography>
                            </Paper>
                        </Grid>
                        <Grid item lg={3} xs={12}>
                            <Paper className={classes.paper}>
                                <UserNameForm/>
                            </Paper>
                        </Grid>
                        <Grid item lg={3} xs={12}>
                            <Paper className={classes.paper}>
                                <UserEmailForm/>
                            </Paper>
                        </Grid>
                        <Grid item lg={3} xs={12}>
                            <Paper className={classes.paper}>
                                <UserPasswordForm/>
                            </Paper>
                        </Grid>
                        <Grid item lg={3} xs={12}>
                            <Paper className={classes.paper}>
                                <UserAvatarChangeForm/>
                            </Paper>
                        </Grid>
                    </Grid>
                </main>
            </React.Fragment>
        );
    }
}

export default withStyles(styles)(withSnackbar(UserPanel));