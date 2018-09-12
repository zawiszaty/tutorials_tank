import React from 'react';
import PropTypes from 'prop-types';
import Avatar from '@material-ui/core/Avatar';
import Button from '@material-ui/core/Button';
import CssBaseline from '@material-ui/core/CssBaseline';
import FormControl from '@material-ui/core/FormControl';
import Input from '@material-ui/core/Input';
import InputLabel from '@material-ui/core/InputLabel';
import SupervisedUserCircle from '@material-ui/icons/SupervisedUserCircle';
import Paper from '@material-ui/core/Paper';
import Typography from '@material-ui/core/Typography';
import withStyles from '@material-ui/core/styles/withStyles';


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

class Registration extends React.Component {
    constructor(props) {
        super(props);

        this.state = {
            name: '',
            password: '',
            showPassword: false
        };

        this.handleNameChange = this.handleNameChange.bind(this);
        this.handleClickShowPassword = this.handleClickShowPassword.bind(this);
    }

    handleNameChange(state) {
        this.setState({
            name: state.target.value
        })
    }

    handleMouseDownPassword(event) {
        event.preventDefault();
    };

    handleClickShowPassword() {
        this.setState(state => ({showPassword: !state.showPassword}));
    };

    render() {
        const classes = this.props.classes;
        return (
            <React.Fragment>
                <CssBaseline/>
                <main className={classes.layout}>
                    <Paper className={classes.paper}>
                        <Avatar className={classes.avatar}>
                            <SupervisedUserCircle/>
                        </Avatar>
                        <Typography variant="headline">Zarejestruj się</Typography>
                        <form className={classes.form}>
                            <FormControl margin="normal" required fullWidth>
                                <InputLabel htmlFor="email">Email</InputLabel>
                                <Input id="email" name="email" autoComplete="email" autoFocus/>
                            </FormControl>
                            <FormControl margin="normal" required fullWidth>
                                <InputLabel htmlFor="username">Nazwa Użytkownika</InputLabel>
                                <Input id="username" name="username" />
                            </FormControl>
                            <FormControl margin="normal" required fullWidth>
                                <InputLabel htmlFor="password">Hasło</InputLabel>
                                <Input
                                    name="password"
                                    type="password"
                                    id="passwordSecond"
                                    autoComplete="current-password"
                                />
                            </FormControl>
                            <FormControl margin="normal" required fullWidth>
                                <InputLabel htmlFor="password">Powtórz Hasło</InputLabel>
                                <Input
                                    name="password"
                                    type="password"
                                    id="passwordSecond"
                                    autoComplete="current-password"
                                />
                            </FormControl>
                            <Button
                                type="submit"
                                fullWidth
                                variant="raised"
                                color="primary"
                                className={classes.submit}
                            >
                                Zarejestruj
                            </Button>
                        </form>
                    </Paper>
                </main>
            </React.Fragment>
        );
    }
}

export default withStyles(styles)(Registration);