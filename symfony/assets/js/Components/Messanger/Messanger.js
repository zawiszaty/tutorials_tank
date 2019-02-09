import React from 'react';
import CssBaseline from '@material-ui/core/CssBaseline';
import Paper from '@material-ui/core/Paper';
import withStyles from '@material-ui/core/styles/withStyles';
import Button from "@material-ui/core/Button/Button";
import axios from './../../axios';
import Card from '@material-ui/core/Card'
import TextField from '@material-ui/core/TextField';
import connect from "react-redux/es/connect/connect";

const styles = theme => ({
    footer: {
        marginTop: '20px',
        textAlign: 'center',
        borderRadius: '0',
        fontSize: '1.5rem',
        display: 'flex',
        justifyContent: "center",
        alignItems: 'center',
        flexDirection: "column"
    },
    messanger: {
        width: '80%',
        flexDirection: "column",
        maxHeight: "60vh",
        overflow: "auto",
    },
    messanger__button: {
        width: '80%'
    },
    card: {
        width: '80%',
    },
    card__sender: {
        marginLeft: "auto"
    }
});

class Messanger extends React.Component {
    constructor(props) {
        super(props);
        this.state = {
            notif: new WebSocket(`ws://0.0.0.0:8123?token=${localStorage.getItem('token')}`),
            messages: {},
            loading: true,
            content: ''
        };

        console.log(this.props.user);
        this.state.notif.onmessage = (event) => {
            console.log(event.data);
            let messages = this.state.messages;
            let message = JSON.parse(event.data);
            messages.splice(0,0,message);
            this.setState({
                messages
            })
        };
        this.state.notif.onopen = () => {
            // var myJsonString = JSON.stringify({
            //     "userData": {
            //         "token": `${localStorage.getItem('token')}`,
            //         "recipient": props.match.params.user,
            //         "content": "tes"
            //     }
            // });
            // this.state.notif.send(myJsonString);
            console.log('conected');
        };

        this.state.notif.onerror = function (error) {
            console.log(error);
            //alert('error');
        }
    }

    componentDidMount = () => {
        this.getAllMessage();
    };

    getAllMessage = () => {
        axios.get('/api/v1/message?limit=20', {
            headers: {'Authorization': 'Bearer ' + localStorage.getItem('token')}
        }).then((response) => {
            this.setState({
                messages: response.data.data,
                loading: false,
            })
        })
    };

    sendMessage = (e) => {
        e.preventDefault();
        var myJsonString = JSON.stringify({
            "userData": {
                "token": `${localStorage.getItem('token')}`,
                "recipient": this.props.match.params.user,
                "content": this.state.content
            }
        });
        this.state.notif.send(myJsonString);
        let messages = this.state.messages;
        let message = {
            content: this.state.content,
            sender: {
                id: this.props.user.id,
                username: this.props.user.username,
                avatar: this.props.user.avatar,
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
        const classes = this.props.classes;
        return (
            <React.Fragment>
                <CssBaseline/>
                <Paper className={classes.footer} color="primary">
                    <Paper className={classes.messanger} color="primary">
                        {this.state.loading !== true &&
                        <React.Fragment>
                            {this.state.messages.slice(0).reverse().map((message) => {
                                return (
                                    <React.Fragment>
                                        {console.log(this.props.user.id, message)}
                                        {message.sender.id === this.props.user.id &&
                                        <Card className={classes.card + " " + classes.card__sender}>
                                            {message.content}
                                        </Card>
                                        }
                                        {message.sender.id !== this.props.user.id &&
                                        <Card className={classes.card + " "}>
                                            {message.content}
                                        </Card>
                                        }
                                    </React.Fragment>
                                )
                            })}
                        </React.Fragment>
                        }
                    </Paper>
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
            </React.Fragment>
        );
    }
}

const mapStateToProps = state => ({
    user: state.user
});

const mapActionToProps = {};
export default connect(mapStateToProps, mapActionToProps)(withStyles(styles)(Messanger));