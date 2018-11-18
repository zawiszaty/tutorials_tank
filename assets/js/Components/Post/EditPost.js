import React from 'react';
import withStyles from '@material-ui/core/styles/withStyles';
import {connect} from 'react-redux';
import Paper from '@material-ui/core/Paper';
import Avatar from "@material-ui/core/Avatar/Avatar";
import LockIcon from "@material-ui/core/SvgIcon/SvgIcon";
import Typography from "@material-ui/core/Typography/Typography";
import CKEditor from '@ckeditor/ckeditor5-react';
import ClassicEditor from '@ckeditor/ckeditor5-build-classic';
import Button from "@material-ui/core/Button/Button";
import TextField from "@material-ui/core/TextField/TextField";
import axios from './../../axios';
import {withSnackbar} from "notistack";
import Tabs from '@material-ui/core/Tabs';
import Tab from '@material-ui/core/Tab';
import UploadThumbnailForm from './UploadThumbnailForm';
import SelectCategory from './SelectCategory';
// The editor core
import Editor, {Editable, createEmptyState} from 'ory-editor-core'
import 'ory-editor-core/lib/index.css' // we also want to load the stylesheets

// Require our ui components (optional). You can implement and use your own ui too!
import {Trash, DisplayModeToggle, Toolbar} from 'ory-editor-ui'
import 'ory-editor-ui/lib/index.css'

// Load some exemplary plugins:
import slate from 'ory-editor-plugins-slate' // The rich text area plugin
import 'ory-editor-plugins-slate/lib/index.css' // Stylesheets for the rich text area plugin
import parallax from 'ory-editor-plugins-parallax-background' // A plugin for parallax background images
import 'ory-editor-plugins-parallax-background/lib/index.css' // Stylesheets for parallax background images
import Checkbox from '@material-ui/core/Checkbox';
import {
    BrowserRouter as Router,
    Route,
    Link,
    Redirect,
    withRouter
} from "react-router-dom";


// Define which plugins we want to use. We only have slate and parallax available, so load those.
const plugins = {
    content: [slate()], // Define plugins for content cells. To import multiple plugins, use [slate(), image, spacer, divider]
    layout: [parallax({defaultPlugin: slate()})] // Define plugins for layout cells
}

// Creates an empty editable
const content = createEmptyState()

// Instantiate the editor
const editor = new Editor({
    plugins,
    // pass the content state - you can add multiple editables here
    editables: [content],
})

const styles = theme => ({
    root: {
        flexGrow: 1,
    },
    grow: {
        flexGrow: 1,
    },
    menuButton: {
        marginLeft: -12,
        marginRight: 20,
        color: '#fff',
        textdecoration: '0'
    },
    layout: {
        width: 'auto',
        display: 'block', // Fix IE11 issue.
        marginLeft: theme.spacing.unit * 3,
        marginRight: theme.spacing.unit * 3,
        [theme.breakpoints.up(400 + theme.spacing.unit * 3 * 2)]: {
            width: '100%',
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
});

let data;

function TabContainer(props) {
    return (
        <Typography component="div" style={{padding: 8 * 3}}>
            {props.children}
        </Typography>
    );
}

class EditPost extends React.Component {
    constructor(props) {
        super(props);
        this.state = {
            content: '',
            title: '',
            data: new FormData(),
            thumbnail: '',
            category: '',
            titleError: false,
            value: '',
            description: false,
            descriptionError: false,
            loading: true,
        };

    }

    componentDidMount = () => {
        this.handlePost();
    };

    handlePost = () => {
        axios.get(`/api/v1/post/${this.props.match.params.id}`)
            .then((response) => {
                console.log(this.props.user.id);
                console.log(response.data.user);
                if(this.props.user.id != response.data.user) {
                    this.props.onPresentSnackbar('error', 'Nie twój post mordo');
                    return <Redirect to="/" />
                }

                this.setState({
                    content: response.data.content,
                    title: response.data.title,
                    thumbnail: response.data.thumbnail,
                    category: response.data.category,
                    value: response.data.type,
                    description: response.data.shortDescription,
                    loading: false,
                })
            });
    };

    handleChange = (event, value) => {
        this.setState({
            value
        })
    };

    handleChangeAvatar = (data) => {
        this.setState({
            data
        });
    };

    handleChangeCategory = (category) => {
        console.log(category);
        this.setState({category});
    };

    render() {
        const classes = this.props.classes;

        const descriptionForm = <React.Fragment>
            <TextField
                error={this.state.descriptionError}
                label="Krótki opis"
                helperText={<span>Pole nie moze być puste</span>}
                type="text"
                value={this.state.description}
                onChange={(e) => {
                    this.setState({
                        description: e.target.value
                    })
                }}
                rows={2}
                rowsMax={8}
            />
        </React.Fragment>;

        return (
            <React.Fragment>
                <div className={classes.root}>
                    <Paper className={classes.paper}>
                        {this.state.loading !== true &&
                        <React.Fragment>
                            <Typography variant="headline">Dodaj post</Typography>
                            {this.state.value === 'oder_site' && <TabContainer>
                                <TextField
                                    error={this.state.titleError}
                                    label="Tytuł"
                                    helperText={<span>Pole nie moze być puste</span>}
                                    type="text"
                                    value={this.state.title}
                                    onChange={(e) => {
                                        this.setState({
                                            title: e.target.value
                                        })
                                    }}
                                />
                                <TextField
                                    error={this.state.titleError}
                                    label="Link"
                                    helperText={<span>Pole nie moze być puste</span>}
                                    type="text"
                                    value={this.state.content}
                                    onChange={(e) => {
                                        this.setState({
                                            content: `<iframe src="${e.target.value}" width="100%" height="100%"></iframe>`
                                        })
                                    }}
                                />
                                {descriptionForm}
                                <UploadThumbnailForm handleChangeAvatar={this.handleChangeAvatar} thumbnail={this.state.thumbnail}/>
                                <SelectCategory handleChangeCategory={this.handleChangeCategory}
                                                category={this.state.category}/>
                                <Button
                                    fullWidth
                                    variant="raised"
                                    color="secondary"
                                    onClick={() => {
                                        if (this.state.title === '') {
                                            this.setState({
                                                titleError: true
                                            })
                                        } else if (this.state.description === '') {
                                            this.setState({
                                                descriptionError: true
                                            })
                                        } else {
                                            if (this.state.content !== '') {
                                                this.state.data.append("content", this.state.content);
                                                this.state.data.append("title", this.state.title);
                                                this.state.data.append("shortDescription", this.state.description);
                                                this.state.data.append("type", 'oder_site');
                                                this.state.data.append("category", this.state.category);

                                                axios.post(`/api/v1/post/${this.props.match.params.id}`, this.state.data, {
                                                    headers: {'Authorization': 'Bearer ' + localStorage.getItem('token')}
                                                }).then((response) => {
                                                    this.props.onPresentSnackbar('success', 'Post dodano');
                                                })
                                            }
                                        }
                                    }}
                                >
                                    Dodaj post
                                </Button>
                            </TabContainer>}
                            {this.state.value === 'own_post' && <TabContainer>
                                <TextField
                                    error={this.state.titleError}
                                    label="Tytuł"
                                    helperText={<span>Pole nie moze być puste</span>}
                                    type="text"
                                    value={this.state.title}
                                    onChange={(e) => {
                                        this.setState({
                                            title: e.target.value
                                        })
                                    }}
                                />
                                {descriptionForm}
                                <CKEditor
                                editor={ClassicEditor}
                                onInit={editor => {
                                // You can store the "editor" and use when it is needed.
                                console.log('Editor is ready to use!', editor);
                                }}
                                data={this.state.content}
                                onChange={(event, editor) => {
                                data = editor.getData();
                                this.setState({
                                content: data
                                });

                                console.log({event, editor, data});
                                }}
                                />
                                {/*/!* Content area *!/*/}
                                {/*<Editable editor={editor} id={content.id}/>*/}

                                {/*/!*  Default user interface  *!/*/}
                                {/*<Trash editor={editor}/>*/}
                                {/*<DisplayModeToggle editor={editor}/>*/}
                                {/*<Toolbar editor={editor}/>*/}

                                <UploadThumbnailForm handleChangeAvatar={this.handleChangeAvatar} thumbnail={this.state.thumbnail}/>
                                <SelectCategory handleChangeCategory={this.handleChangeCategory}
                                                category={this.state.category}/>
                                <Button
                                    fullWidth
                                    variant="raised"
                                    color="secondary"
                                    onClick={() => {
                                        if (this.state.title === '') {
                                            this.setState({
                                                titleError: true
                                            })
                                        } else if (this.state.description === '') {
                                            this.setState({
                                                descriptionError: true
                                            })
                                        } else {
                                            if (this.state.content !== '') {
                                                this.state.data.append("content", this.state.content);
                                                this.state.data.append("title", this.state.title);
                                                this.state.data.append("type", 'own_post');
                                                this.state.data.append("shortDescription", this.state.description);
                                                this.state.data.append("category", this.state.category);

                                                axios.post(`/api/v1/post/${this.props.match.params.id}`, this.state.data, {
                                                    headers: {'Authorization': 'Bearer ' + localStorage.getItem('token')}
                                                }).then((response) => {
                                                    this.props.onPresentSnackbar('success', 'Post dodano');
                                                })
                                            }
                                        }
                                    }}
                                >
                                    Dodaj post
                                </Button>
                            </TabContainer>}
                        </React.Fragment>
                        }
                    </Paper>
                </div>
            </React.Fragment>
        );
    }
}

const mapStateToProps = state => ({
    user: state.user
});

export default connect(mapStateToProps)(withStyles(styles)(withSnackbar(EditPost)));