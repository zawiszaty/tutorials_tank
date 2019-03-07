import React, {Component} from 'react';
import PropTypes from 'prop-types';
import {Paper} from "@material-ui/core";
import withStyles from "@material-ui/core/styles/withStyles";
import axios from './../../axios/axios';
import {toast} from "react-toastify";
import {withRouter} from "react-router-dom";
import Typography from "@material-ui/core/Typography";
import Button from "@material-ui/core/Button";
import Grid from "@material-ui/core/Grid";
import Avatar from "@material-ui/core/Avatar";
import green from '@material-ui/core/colors/green';

const styles = theme => ({
    main: {
        width: 'auto',
        display: 'block', // Fix IE 11 issue.
        marginLeft: theme.spacing.unit * 3,
        marginRight: theme.spacing.unit * 3,
        [theme.breakpoints.up(400 + theme.spacing.unit * 3 * 2)]: {
            width: 400,
            marginLeft: 'auto',
            marginRight: 'auto',
        },
    },
    paper: {
        width: "auto",
        marginTop: theme.spacing.unit * 8,
        margin: "auto",
        display: 'flex',
        flexDirection: 'column',
        alignItems: 'center',
        padding: `${theme.spacing.unit * 2}px ${theme.spacing.unit * 3}px ${theme.spacing.unit * 3}px`,
    },
    avatar: {
        width: '200px',
        height: '200px',
        margin: theme.spacing.unit,
        backgroundColor: theme.palette.secondary.main,
    },
    form: {
        width: '100%', // Fix IE 11 issue.
        marginTop: theme.spacing.unit,
    },
    submit: {
        marginTop: theme.spacing.unit * 3,
    },
    button: {
        backgroundColor: green[500],
        marginTop: "2em",
        color: "#ffffff",
    }
});

class SingleUser extends Component {
    state = {
        user: [],
        loading: true,
    };

    componentDidMount() {
        this.getUser();
    }

    componentWillReceiveProps(nextProps, nextContext) {
        this.getUser();
    }

    getUser = () => {
        axios.get(`/api/v1/user/${this.props.match.params.username}`)
            .then((response) => [
                this.setState({
                    user: response.data,
                    loading: false,
                })
            ]).catch((e) => {
            toast.info("Nie ma takiego uzytkownika", {
                position: toast.POSITION.BOTTOM_RIGHT
            });
            this.props.history.push(`/`)
        })
        ;
    };

    render() {
        const {classes} = this.props;
        return (
            <Paper className={classes.paper}>
                {this.state.loading === false &&
                <React.Fragment>
                    <Avatar className={classes.avatar} src={this.state.user.avatar}/>
                    <Typography variant="h1" gutterBottom>
                        {this.state.user.username}
                    </Typography>
                    <Grid container>
                        {this.state.user.roles.includes('ROLE_ADMIN') === true &&
                        <Grid item md={4}>
                            <Button variant="outlined" color="secondary" component="span"
                                    onClick={() => {
                                        this.props.history.push(`/wiadomosci/${this.state.user.id}`);
                                    }}
                                    fullWidth>
                                Użytkownik jest adminem
                            </Button>
                        </Grid>
                        }
                        <Grid item md={4}>
                            <Button variant="flat" color="primary" component="span"
                                    onClick={() => {
                                        this.props.history.push(`/wiadomosci/${this.state.user.id}`);
                                    }}
                                    fullWidth>
                                Wyślij wiadomość
                            </Button>
                        </Grid>
                        <Grid item md={4}>
                            <Button variant="flat" color="default" component="span"
                                    onClick={() => {
                                        this.props.history.push(`/posty/uzytkownika/${this.state.user.id}`);
                                    }}
                                    fullWidth>
                                Zobacz posty
                            </Button>
                        </Grid>
                    </Grid>
                    <Button variant="flat" className={classes.button} component="span"
                            onClick={() => {
                                this.props.history.goBack();
                            }}
                            fullWidth>
                        Wróć
                    </Button>
                </React.Fragment>
                }
            </Paper>
        );
    }
}

SingleUser.propTypes = {};

export default withRouter(withStyles(styles)(SingleUser));