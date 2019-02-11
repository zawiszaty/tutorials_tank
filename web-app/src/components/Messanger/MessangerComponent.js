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
        zIndex: "10000",
        textAlign: "center",
    },
    card__sender: {
        marginLeft: "auto"
    },
    card__avatar: {
        marginRight: "2em",
        height: '4em',
        width: '4em',
    }
});


class MessangerComponent extends Component {
    constructor(props) {
        super(props);
        this.state = {
            content: '',
            notif: new WebSocket(`ws://0.0.0.0:8123?token=${localStorage.getItem('token')}`),
            messages: props.messages
        };
        this.state.notif.onmessage = (event) => {
            let messages = this.state.messages;
            let message = JSON.parse(event.data);
            messages.splice(0, 0, message);
            this.setState({
                messages
            })
        };
        this.state.notif.onopen = () => {
            console.log('conected');
        };

        this.state.notif.onerror = function (error) {
            console.log(error);
        }
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
                        {this.state.messages.length > 0 && <React.Fragment>
                            {this.state.messages.slice(0).reverse().map((message) => {
                                return (
                                    <React.Fragment>
                                        {message.sender.id === this.props.user[0].id &&
                                        <Card className={classes.card + " " + classes.card__sender}>
                                            <Typography variant="subtitle1" gutterBottom>
                                                <Avatar className={classes.card__avatar}
                                                        src={'http://localhost:9999/' + message.sender.avatar}/>
                                                {message.sender.username}
                                            </Typography>
                                            {message.content}
                                        </Card>
                                        }
                                        {message.sender.id !== this.props.user[0].id &&
                                        <Card className={classes.card + " "}>
                                            <Typography variant="subtitle1" gutterBottom>
                                                <Avatar className={classes.card__avatar}
                                                        src={'http://localhost:9999/' + message.sender.avatar}/>
                                                {message.sender.username}
                                            </Typography>
                                            {message.content}
                                        </Card>
                                        }
                                    </React.Fragment>
                                )
                            })}
                        </React.Fragment>}
                        <form onSubmit={this.sendMessage}>
                            <TextField
                                required
                                id="standard-required"
                                label="Wiadomość"
                                margin="normal"
                                value={this.state.content}
                                onChange={this.handleChangeInput}
                            />
                            <Button
                                type="submit"
                                className={classes.messanger__button}
                                variant="raised"
                                color="primary"
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