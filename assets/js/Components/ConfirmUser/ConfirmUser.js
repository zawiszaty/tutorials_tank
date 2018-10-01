import React from 'react';
import CssBaseline from '@material-ui/core/CssBaseline';
import Paper from '@material-ui/core/Paper';
import withStyles from '@material-ui/core/styles/withStyles';
import axios from './../../axios';
import {withSnackbar} from 'notistack';
import Avatar from "@material-ui/core/Avatar/Avatar";
import SupervisedUserCircle from '@material-ui/icons/SupervisedUserCircle';
import Typography from "@material-ui/core/Typography/Typography";
import CircularProgress from '@material-ui/core/CircularProgress';
import Button from "@material-ui/core/Button/Button";
import {NavLink} from "react-router-dom";
import Drawer from "@material-ui/core/Drawer/Drawer";

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

class ConfirmUser extends React.Component {
    constructor(props) {
        super(props);
        const {onPresentSnackbar, match} = this.props;
        console.log(match.params.token);
        this.state = {
            onPresentSnackbar: onPresentSnackbar,
            loaded: true,
            error: false,
            errorType: ''
        };
    }

    componentDidMount() {
        this.confirmUser(this.props.match.params.token)
    }

    confirmUser(token) {
        axios.post('/api/v1/user/confirm/' + token)
            .then((response) => {
                console.log(response.data)
                this.setState({
                    loaded: false,
                })
            })
            .catch((e) => {
                console.log(e.response.data.errors.title);
                this.setState({
                    loaded: false,
                    error: true,
                    errorType: e.response.data.errors.title
                })
            })
    }

    render() {
        const classes = this.props.classes;
        let loaded = '';
        let view = '';
        const isEnabled = <Typography variant="headline">User jest juz potwierdzony</Typography>
        const error = <Typography variant="headline">Coś poszło nie tak</Typography>
        if (this.state.loaded) {
            loaded = <CircularProgress className={classes.progress} color="secondary"/>
        } else {
            if (this.state.error) {
                if (this.state.errorType === 'App.Domain.User.Exception.UserIsEnabledException') {
                    view = isEnabled;
                } else {
                    view = error;
                }
            } else {
                view = <Typography variant="headline">Konto potwierdzone możesz sie teraz zalogować
                    <NavLink to="/login">
                        <Button
                            fullWidth
                            variant="raised"
                            color="primary"
                        >
                            Zaloguj się
                        </Button>
                    </NavLink>
                </Typography>
            }
        }
        return (
            <React.Fragment>
                <CssBaseline/>
                <main className={classes.layout}>
                    <Paper className={classes.paper}>
                        {loaded}
                        {view}
                    </Paper>
                </main>
            </React.Fragment>
        );
    }
}

export default withStyles(styles)(withSnackbar(ConfirmUser));