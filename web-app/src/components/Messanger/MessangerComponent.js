import React, {Component} from 'react';
import PropTypes from 'prop-types';
import Paper from "@material-ui/core/Paper";
import CircularProgress from "@material-ui/core/CircularProgress";
import CssBaseline from "@material-ui/core/CssBaseline";
import {withStyles} from "@material-ui/core";
import axios from "../../axios/axios";
import Typography from "@material-ui/core/Typography";
import {login} from "../../actions/user";
import {connect} from "react-redux";
import CategoryList from "../../components/Post/CategoryList";
import Card from "@material-ui/core/Card";
import Button from "@material-ui/core/Button";
import TextField from "@material-ui/core/TextField";
import Avatar from "@material-ui/core/Avatar";
import {toast} from "react-toastify";
import {baseSocket, baseUrl} from "../../axios/env";

const styles = theme => ({
    progress: {
        margin: theme.spacing.unit * 2,
    },
    main: {
        width: 'auto',
        display: 'block', // Fix IE 11 issue.
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
        width: '100%', // Fix IE 11 issue.
        marginTop: theme.spacing.unit,
    },
    submit: {
        marginTop: theme.spacing.unit * 3,
    },
    messanger: {
        width: '80%',
        flexDirection: "column",
        maxHeight: "60vh",
        overflow: "auto",
        zIndex: "-10000",
    },
    messanger__button: {
        width: '80%'
    },
    card: {
        width: '80%',
        display: "flex",
        marginTop: "3em",
        margin: 'auto',
    },
    card__sender: {
        marginRight: "0"
    },
    card__recipent: {
        marginLeft: '0',
    },
    card__avatar: {
        width: "2em",
        height: "2em",
        margin: "auto",
    },
    avatar_bar: {
        width: '40%',
        display: 'flex',
        flexDirection: "column",
        justifyContent: "center",
        textAlign: "center",
    },
    bar_config: {
        width: '100%',
        textAlign: "center",
    },
    text_form: {
        width: '80%',
    }
});


class MessangerComponent extends Component {
    constructor(props) {
        super(props);
        this.state = {
            content: '',
            notif: new WebSocket(`ws://${baseSocket}:8123?token=${localStorage.getItem('token')}`),
            messages: props.messages
        };
        this.state.notif.onmessage = (event) => {
            try {
                let messages = this.state.messages;
                let message = JSON.parse(event.data);
                messages.splice(0, 0, message);
                this.setState({
                    messages
                });
                props.upLimit(1);
            } catch (e) {
                return false;
            }
        };
        this.state.notif.onopen = () => {
            console.log('conected');
        };

        this.state.notif.onerror = function (error) {
            console.log(error);
        }
    }

    componentWillReceiveProps(nextProps, nextContext) {
        this.setState({
            messages: this.props.messages
        })
    }

    sendMessage = (e) => {
        e.preventDefault();
        var myJsonString = JSON.stringify({
            "userData": {
                "token": `${localStorage.getItem('token')}`,
                "recipient": this.props.id,
                "content": this.state.content
            }
        });
        this.state.notif.send(myJsonString);
        let messages = this.state.messages;
        let message = {
            content: this.state.content,
            sender: {
                id: this.props.user[0].id,
                username: this.props.user[0].name,
                avatar: this.props.user[0].avatar,
            },
            createAt: new Date(),
        };

        messages.splice(0, 0, message);
        this.setState({
            messages,
            content: '',
        })
    };

    handleChangeInput = (e) => {
        this.setState({
            content: e.target.value
        })
    };

    render() {
        const {classes} = this.props;

        return (
            <div>
                <main className={classes.main}>
                    <CssBaseline/>
                    <Paper className={classes.paper}>
                        <Button variant="outlined" color="primary" fullWidth
                                onClick={() => {
                                    if (this.state.messages.length < this.props.totalMessages) {
                                        this.props.upLimit(10);
                                    } else {
                                        toast.info("Nie ma wiecej wiadomosci", {
                                            position: toast.POSITION.BOTTOM_RIGHT
                                        });
                                    }
                                }}
                        >
                            Wczytaj wiecej wiadomości
                        </Button>
                        {this.state.messages.length > 0 && <React.Fragment>
                            {this.state.messages.slice(0).reverse().map((message) => {
                                return (
                                    <React.Fragment>
                                        {message.sender.id === this.props.user[0].id &&
                                        <Button variant="outlined"
                                                className={classes.card + " " + classes.card__sender}>
                                            <div className={classes.avatar_bar}>
                                                <Avatar className={classes.card__avatar}
                                                        src={message.sender.avatar}/>
                                                <Typography variant="subtitle1" gutterBottom>
                                                    {message.sender.username}
                                                </Typography>
                                            </div>
                                            <div className={classes.bar_config}>
                                                {message.content}
                                            </div>
                                        </Button>
                                        }
                                        {message.sender.id !== this.props.user[0].id &&
                                        <Button variant="outlined"
                                                className={classes.card + ' ' + classes.card__recipent}>
                                            <div className={classes.avatar_bar}>
                                                <Avatar className={classes.card__avatar}
                                                        src={message.sender.avatar}/>
                                                <Typography variant="subtitle1" gutterBottom>
                                                    {message.sender.username}
                                                </Typography>
                                            </div>
                                            <div className={classes.bar_config}>
                                                {message.content}
                                            </div>
                                        </Button>
                                        }
                                    </React.Fragment>
                                )
                            })}
                        </React.Fragment>}
                        <form onSubmit={this.sendMessage} className={classes.text_form}>
                            <TextField
                                required
                                id="standard-required"
                                label="Wiadomość"
                                margin="normal"
                                value={this.state.content}
                                onChange={this.handleChangeInput}
                                fullWidth
                            />
                            <Button
                                type="submit"
                                variant="raised"
                                color="primary"
                                fullWidth
                            >
                                Wyślij wiadomość
                            </Button>
                        </form>
                    </Paper>
                </main>
            </div>
        );
    }
}

MessangerComponent.propTypes = {};

const mapStateToProps = (state) => {
    return {
        user: state.user
    }
};
const mapDispatchToProps = {login};

export default connect(mapStateToProps, mapDispatchToProps)(withStyles(styles)(MessangerComponent));