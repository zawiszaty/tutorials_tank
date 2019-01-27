import React from 'react';
import Button from '@material-ui/core/Button';
import {ValidatorForm, TextValidator} from 'react-material-ui-form-validator';
import FormControl from "../../containers/Registtration/Registration";
import withStyles from "@material-ui/core/es/styles/withStyles";
import CircularProgress from "@material-ui/core/CircularProgress";
import classNames from 'classnames';
import green from '@material-ui/core/colors/green';
import axios from './../../axios/axios';
import Redirect from "react-router-dom/es/Redirect";
import {ToastContainer, toast} from 'react-toastify';
import {client_id, client_secret} from "./../../axios/env";
import {login} from "../../actions/user";
import {connect} from "react-redux";
import CategoryList from "./CategoryList";
import PostThumbnailForm from "./PostThumbnailForm";
import { Editor } from '@tinymce/tinymce-react';

const styles = theme => ({
    form: {
        width: '100%', // Fix IE 11 issue.
        marginTop: theme.spacing.unit,
    },
    wrapper: {
        margin: theme.spacing.unit,
        position: 'relative',
        display: 'flex',
        alignItems: 'center',
    },
    buttonSuccess: {
        backgroundColor: green[500],
        '&:hover': {
            backgroundColor: green[700],
        },
    },
    buttonProgress: {
        color: green[500],
        position: 'absolute',
        top: '50%',
        left: '50%',
        marginTop: -12,
        marginLeft: -12,
    },
});

class OwnPostForm extends React.Component {
    constructor(props) {
        super(props);
        this.state = {
            title: '',
            content: '',
            shortDescription: '',
            loading: false,
            success: false,
            user: {},
            selected: null,
            file: '',
        };
    }

    handleChangeFile = (file) => {
        this.setState({file});
    };

    handleChange = (event) => {
        const email = event.target.value;
        this.setState({email});
    };

    handleChangeTitle = (event) => {
        const title = event.target.value;
        this.setState({title});
    };

    handleChangeContent = (event) => {
        const content = event.target.getContent();
        this.setState({content});
        console.log(this.state.content);
    };
    handleShortDescription = (event) => {
        const shortDescription = event.target.value;
        this.setState({shortDescription});
    };

    handleSubmit = () => {
        if(this.state.selected === null) {
            toast.info("Wybierz jakaś kategorie", {
                position: toast.POSITION.BOTTOM_RIGHT
            });
        } else {
            if(this.state.file === '') {
                toast.info("Dodaj miniature", {
                    position: toast.POSITION.BOTTOM_RIGHT
                });
            } else {
                if (!this.state.loading) {
                    this.setState(
                        {
                            success: false,
                            loading: true,
                        })
                }
                this.state.file.append('title', this.state.title);
                this.state.file.append('content', this.state.content);
                this.state.file.append('shortDescription', this.state.shortDescription);
                this.state.file.append("type", 'own_post');
                this.state.file.append("category", this.state.selected);
                axios.post('/api/v1/post', this.state.file, {
                    headers: {'Authorization': 'Bearer ' + localStorage.getItem('token')}
                }).then((response) => {
                    toast.success("Dodano post", {
                        position: toast.POSITION.BOTTOM_RIGHT
                    });
                    this.setState({
                        success: true
                    });
                }).catch((e) => {
                    toast.error("Coś poszło nie tak", {
                        position: toast.POSITION.BOTTOM_RIGHT
                    });
                });
            }
        }

    };

    handleClick = (event, id) => {
        const {selected} = this.state;

        if (selected === id) {
            this.setState({selected: null});
        } else {
            this.setState({selected: id});
        }
    };

    render() {
        const {title, link, shortDescription} = this.state;
        const {classes} = this.props;
        const buttonClassname = classNames({
            [classes.buttonSuccess]: this.state.success,
        });

        if (this.state.success) {
            return (
                <React.Fragment>
                    <Redirect to="/"/>
                </React.Fragment>
            )
        }

        return (
            <ValidatorForm
                ref="form"
                onSubmit={this.handleSubmit}
                className={classes.form}
            >
                <TextValidator
                    label="Tytuł"
                    onChange={this.handleChangeTitle}
                    name="email"
                    value={title}
                    type="text"
                    validators={['required']}
                    errorMessages={['To pole jest wymagane']}
                    margin="normal" fullWidth
                />
                <TextValidator
                    label="Krótki opis"
                    onChange={this.handleShortDescription}
                    name="shortDescription"
                    value={shortDescription}
                    type="text"
                    validators={['required']}
                    errorMessages={['To pole jest wymagane']}
                    margin="normal" fullWidth
                />
                <Editor
                    apiKey="69sluxkknib3n831hobh8k54b5yjjvzaexa4hutx9liz6l2b"
                    init={{
                        plugins: 'link image code',
                        toolbar: 'undo redo | bold italic | alignleft aligncenter alignright | code'
                    }}
                    onChange={this.handleChangeContent}
                />
                <CategoryList selected={this.state.selected} handleClick={this.handleClick}/>
                <PostThumbnailForm handleChangeFile={this.handleChangeFile}/>
                <div className={classes.wrapper}>
                    <Button
                        variant="contained"
                        color="primary"
                        type="submit"
                        disabled={this.state.loading}
                        fullWidth
                        className={buttonClassname}
                    >
                        Dodaj Post
                    </Button>
                    {this.state.loading && <CircularProgress size={24} className={classes.buttonProgress}/>}
                </div>
            </ValidatorForm>
        );
    }
}

const mapStateToProps = (state) => {
    return {
        user: state.user
    }
};
const mapDispatchToProps = {login};

export default connect(mapStateToProps, mapDispatchToProps)(withStyles(styles)(OwnPostForm));
